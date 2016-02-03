<?php
/**
 * /src/App/Application.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App;

// Silex application
use Silex\Application as SilexApplication;

// Silex specified providers
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityJWTServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

// 3rd party providers
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use JDesrosiers\Silex\Provider\SwaggerServiceProvider;
use Sorien\Provider\PimpleDumpProvider;
use M1\Vars\Provider\Silex\VarsServiceProvider;

// Application specified providers
use App\Providers\UserProvider;
use App\Services\Loader;

/**
 * Class Application
 *
 * Main application class that is used to run application. Class bootstraps application all providers, mount routes,
 * etc.
 *
 * @category    Core
 * @package     App
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Application extends SilexApplication
{
    /**
     * Project root directory, determined via this file
     *
     * @var string
     */
    private $rootDir;

    /**
     * Current environment which is used to run application.
     *
     * @var string
     */
    private $env;

    /**
     * Application constructor.
     *
     * @param string    $env
     */
    public function __construct($env)
    {
        // Set private vars
        $this->rootDir = __DIR__ . '/../../';
        $this->env = $env;

        // Construct Silex application
        parent::__construct();

        // Create application configuration
        $this->applicationConfig();

        // Register all necessary providers
        $this->applicationRegister();

        // Configure application firewall
        $this->applicationFirewall();

        // Load services
        $this->loadServices();

        // Attach application mount points
        $this->applicationMount();

        // Attach CORS to application
        $this->after($this['cors']);
    }

    /**
     * Getter method for 'rootDir' property.
     *
     * @return  string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Getter method for 'env' property.
     *
     * @return  string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Application configuration.
     *
     * @return  void
     */
    private function applicationConfig()
    {
        // Register configuration provider
        $this->register(
            new VarsServiceProvider($this->rootDir . 'resources/config/' . $this->env . '/config.yml'),
            [
                'vars.options' => [
                    'cache'         => true,
                    'cache_path'    => $this->rootDir . 'var',
                    'cache_expire'  => $this->env === 'dev' ? 0 : 500,
                    'replacements'  => [
                        'rootDir'   => $this->rootDir,
                        'env'       => $this->env,
                    ],
                ],
            ]
        );

        // Set application level values
        $this['debug'] = $this['vars']->get('debug');
        $this['security.jwt'] = $this['vars']->get('security.jwt');
        $this['pimpledump.output_dir'] = $this['vars']->get('pimpledump.output_dir');
    }

    /**
     * Method to register all specified providers for application.
     *
     * @return  void
     */
    private function applicationRegister()
    {
        // Register all providers for application
        $this->register(new ValidatorServiceProvider());
        $this->register(new SecurityServiceProvider());
        $this->register(new SecurityJWTServiceProvider());
        $this->register(new PimpleDumpProvider());
        $this->register(new MonologServiceProvider(), $this['vars']->get('monolog'));
        $this->register(new DoctrineServiceProvider(), $this['vars']->get('database'));
        $this->register(new DoctrineOrmServiceProvider(), $this['vars']->get('orm'));
        $this->register(new SwaggerServiceProvider(), $this['vars']->get('swagger'));
        $this->register(new CorsServiceProvider(), $this['vars']->get('cors'));
    }

    /**
     * Method to setup application firewall.
     *
     * @see http://silex.sensiolabs.org/doc/providers/security.html
     *
     * @return  array
     */
    private function applicationFirewall()
    {
        $entityManager = $this['orm.em'];

        // Set provider for application users
        $this['users'] = function() use ($entityManager) {
            return new UserProvider($entityManager);
        };

        // Security Firewalls configuration
        $this['security.firewalls'] = [
            // Root route
            'root' => [
                'pattern'   => '^/$',
                'anonymous' => true,
            ],
            // Login route
            'login' => [
                'pattern'   => '^/auth/login$',
                'anonymous' => true,
            ],
            // Pimple dump
            'pimpleDump' => [
                'pattern'   => '^/_dump$',
                'anonymous' => true,
            ],
            // API docs are also anonymous
            'docs' => [
                'pattern'   => '^/api/api-docs',
                'anonymous' => true,
            ],
            // And all other routes
            'secured' => [
                'pattern'   => '^.*$',
                'users'     => $this['users'],
                'jwt'       => [
                    'use_forward'               => true,
                    'require_previous_session'  => false,
                    'stateless'                 => true,
                ],
            ],
        ];
    }

    /**
     * Method to attach main mount point to be handled via ControllerProvider.
     *
     * @return  void
     */
    private function applicationMount()
    {
        // Register all application routes
        $this->mount('', new ControllerProvider());
    }

    /**
     * Load shared services.
     *
     * @return  void
     */
    private function loadServices()
    {
        $loader = new Loader($this);
        $loader->bindServicesIntoContainer();
    }
}
