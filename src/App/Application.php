<?php
/**
 * /src/App/Application.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App;

use Silex\Application as SilexApplication;
use Silex\Provider\ValidatorServiceProvider;

/**
 * Class Application
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

    /**
     * Method to register all specified providers for application.
     *
     * @return  void
     */
    private function applicationRegister()
    {
        $this->register(new ValidatorServiceProvider());
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
}
