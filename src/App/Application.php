<?php
/**
 * /src/App/Application.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App;

// Application components
use App\Components\Swagger\SwaggerServiceProvider;
use App\Doctrine\DBAL\Types\UTCDateTimeType;
use App\Providers\UserProvider;
use App\Providers\SecurityServiceProvider;
use App\Services\Loader;

// Silex components
use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityJWTServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

// 3rd components
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use Sorien\Provider\PimpleDumpProvider;
use M1\Vars\Provider\Silex\VarsServiceProvider;

// Symfony components
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// Doctrine components
use Doctrine\DBAL\Types\Type;

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
        $this->config();

        // Attach application listeners
        $this->listeners();

        // Register all necessary providers
        $this->providers();

        // Configure application firewall
        $this->firewall();

        // Load services
        $this->services();

        // Attach application mount points
        $this->mounts();

        // Configure database (DBAL + ORM)
        $this->doctrineConfig();

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
    protected function config()
    {
        $this->checkEnvironmentVariables();

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
     * Method to attach all necessary listeners to application.
     *
     * @return void
     */
    protected function listeners()
    {
        $this['dispatcher']->addListener('kernel.exception', function(GetResponseForExceptionEvent $event) {
            $exception = $event->getException();

            if ($exception instanceof AuthenticationException ||
                $exception instanceof AccessDeniedException ||
                $exception instanceof AuthenticationCredentialsNotFoundException ||
                $exception->getPrevious() instanceof AuthenticationException ||
                $exception->getPrevious() instanceof AccessDeniedException ||
                $exception->getPrevious() instanceof AuthenticationCredentialsNotFoundException
            ) {
                $responseData = [
                    'status'    => method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 401,
                    'message'   => $exception->getMessage(),
                    'code'      => $exception->getCode(),
                ];

                $response = new JsonResponse();
                $response->setData($responseData);
                $response->setStatusCode($responseData['status']);

                $event->setResponse($response);
            }
        });
    }

    /**
     * Method to register all specified providers for application.
     *
     * @return  void
     */
    protected function providers()
    {
        $this->register(new ValidatorServiceProvider());
        $this->register(new SecurityServiceProvider());
        $this->register(new SecurityJWTServiceProvider());
        $this->register(new PimpleDumpProvider());
        $this->register(new MonologServiceProvider(), $this['vars']->get('monolog'));
        $this->register(new DoctrineServiceProvider(), $this['vars']->get('database'));
        $this->register(new DoctrineOrmServiceProvider(), $this['vars']->get('orm'));
        $this->register(new CorsServiceProvider(), $this['vars']->get('cors'));
        $this->register(new SwaggerServiceProvider(), $this['vars']->get('swagger'));
    }

    /**
     * Method to setup application firewall.
     *
     * @see http://silex.sensiolabs.org/doc/providers/security.html
     *
     * @return  array
     */
    protected function firewall()
    {
        $entityManager = $this['orm.em'];
        $app = $this;

        // Set provider for application users
        $this['users'] = function() use ($app, $entityManager) {
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
            // CORS preflight requests
            'cors-preflight' => array(
                'pattern' => $this['cors_preflight_request_matcher'],
            ),
            // API docs are also anonymous
            'docs' => [
                'pattern'   => '^/api',
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
     * Load shared services.
     *
     * @return  void
     */
    protected function services()
    {
        $loader = new Loader($this);
        $loader->bindServices();
    }

    /**
     * Method to attach main mount point to be handled via ControllerProvider.
     *
     * @return  void
     */
    protected function mounts()
    {
        // Register all application routes
        $this->mount('', new ControllerProvider());
    }

    /**
     * Method to configure Doctrine (DBAL + ORM). We'll need to do following steps here:
     *  1) Override default datetime and datetimetz types with custom UTC datetime type
     *      - To ensure that we're storing all the datetimes as in UTC time
     *
     * @throws  \Doctrine\DBAL\DBALException
     *
     * @return  void
     */
    protected function doctrineConfig()
    {
        // Override DateTime and DateTimeTz types
        Type::overrideType('datetime', UTCDateTimeType::class);
        Type::overrideType('datetimetz', UTCDateTimeType::class);
    }

    /**
     * Helper method to set defaults to ENV variables. These are needed for easy environment specified overrides for
     * these values.
     *
     * Note that atm this only supports database connection options and nothing else.
     *
     * @todo What else variables should be in ENV?
     *
     * @return  void
     */
    private function checkEnvironmentVariables()
    {
        // Supported ENV vars and default values for those
        $vars = [
            'DATABASE_DB_OPTIONS_DRIVER'    => 'pdo_mysql',
            'DATABASE_DB_OPTIONS_HOST'      => 'localhost',
            'DATABASE_DB_OPTIONS_DBNAME'    => 'silex_backend',
            'DATABASE_DB_OPTIONS_USER'      => 'silex',
            'DATABASE_DB_OPTIONS_PASSWORD'  => 'silex',
            'DATABASE_DB_OPTIONS_CHARSET'   => 'utf8mb4',
        ];

        foreach ($vars as $key => $value) {
            if (getenv($key) === false) { // Fallback to default value
                putenv($key . '=' . $value);
            }
        }
    }
}
