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

// Application specified providers
use App\Providers\UserProvider;

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

        // Expose application to configuration files
        /** @noinspection PhpUnusedLocalVariableInspection */
        $app = $this;

        // Determine used configuration file
        $configFile = sprintf('%s/resources/config/%s.php', $this->rootDir, $this->env);

        // Oh noes, config file doesn't exists => fatal error
        if (!file_exists($configFile)) {
            throw new \RuntimeException(sprintf('The file "%s" does not exist.', $configFile));
        }

        // Attach configuration file, this will change $app (reference to $this) variable
        /** @noinspection PhpIncludeInspection */
        require $configFile;

        // Create application config values
        $this->applicationConfig();

        // Register all necessary providers
        $this->applicationRegister();

        // Configure application firewall
        $this->applicationFirewall();

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
        // Security JWT configuration
        $this['security.jwt'] = [
            'secret_key'    => 'todo_this_should_be_configurable',
            'life_time'     => 86400,
            'options'       => [
                'username_claim'    => 'identifier',    // default name, option specifying claim containing username
                'header_name'       => 'Authorization', // default null, option for usage normal oauth2 header
                'token_prefix'      => 'Bearer',
            ]
        ];

        // Change output directory of pimple dumper
        $this['pimpledump.output_dir'] = $this->rootDir . 'var/';
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
        $this->register(new MonologServiceProvider(), $this->getMonologServiceProviderOptions());
        $this->register(new SecurityServiceProvider());
        $this->register(new SecurityJWTServiceProvider());
        $this->register(new DoctrineServiceProvider(), $this->getDoctrineServiceProviderOptions());
        $this->register(new DoctrineOrmServiceProvider(), $this->getDoctrineOrmServiceProviderOptions());
        $this->register(new PimpleDumpProvider());
        $this->register(new SwaggerServiceProvider(), $this->getSwaggerServiceProviderOptions());
        $this->register(new CorsServiceProvider(), $this->getCorsServiceProviderOptions());
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
                'pattern'   => '^_dump$',
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
     * Getter method for MonologServiceProvider options.
     *
     * @todo should these be configured via ini file?
     *
     * @see http://silex.sensiolabs.org/doc/providers/monolog.html
     *
     * @return  array
     */
    private function getMonologServiceProviderOptions()
    {
        return [
            'monolog.logfile' => $this->rootDir . '/var/logs/app.log',
        ];
    }

    /**
     * Getter method for DoctrineServiceProvider options.
     *
     * @todo Use ini file for these
     *
     * @see http://silex.sensiolabs.org/doc/providers/doctrine.html
     *
     * @return  array
     */
    private function getDoctrineServiceProviderOptions()
    {
        return [
            'db.options' => [
                'driver'    => 'pdo_mysql',
                'host'      => 'localhost',
                'dbname'    => 'silex_backend',
                'user'      => 'silex',
                'password'  => 'silex',
                'charset'   => 'utf8mb4',
            ],
        ];
    }

    /**
     * Getter method for DoctrineOrmServiceProvider options.
     *
     * @see https://github.com/dflydev/dflydev-doctrine-orm-service-provider#configuration
     *
     * @return  array
     */
    private function getDoctrineOrmServiceProviderOptions()
    {
        return [
            'orm.em.options' => [
                'mappings' => [
                    [
                        'type'                          => 'annotation',
                        'namespace'                     => 'App\Entities',
                        'path'                          => $this->rootDir . 'src/App/Entities',
                        'use_simple_annotation_reader'  => false,
                    ]
                ],
            ],
        ];
    }

    /**
     * Getter method for SwaggerServiceProvider options.
     *
     * @see https://github.com/jdesrosiers/silex-swagger-provider#parameters
     *
     * @return  array
     */
    private function getSwaggerServiceProviderOptions()
    {
        return [
            'swagger.srcDir'        => $this->rootDir . 'vendor/zircote/swagger-php/library',
            'swagger.servicePath'   => $this->rootDir . 'src/',
        ];
    }

    /**
     * Getter method for CorsServiceProvider options.
     *
     * @see https://github.com/jdesrosiers/silex-cors-provider#parameters
     *
     * @return  array
     */
    private function getCorsServiceProviderOptions()
    {
        return [];
    }
}
