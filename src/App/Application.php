<?php
/**
 * /src/App/Application.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App;

use App\Providers\UserProvider;
use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityJWTServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

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

        // Attach application mount points
        $this->applicationMount();
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

    private function applicationConfig()
    {
        $app = $this;

        // Set provider for application users
        $this['users'] = function() use ($app) {
            return new UserProvider($app['db']);
        };

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

        // Security Firewalls configuration
        $this['security.firewalls'] = [
            // Anonymous routes
            'login' => [
                'pattern'   => '^/auth/login$',
                'anonymous' => true,
            ],
            // And all other routes
            'secured' => [
                'pattern'   => '^.*$',
                'jwt'       => [
                    'use_forward'               => true,
                    'require_previous_session'  => false,
                    'stateless'                 => true,
                ],
            ],
        ];
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
     * @todo    should these be configured via ini file?
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
     * @todo    Use ini file for these
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
}
