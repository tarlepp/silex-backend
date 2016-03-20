<?php
/**
 * /src/App/Services/Loader.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Application components
use App\Application;

/**
 * Class Loader
 *
 * @category    Services
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Loader
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Loader constructor.
     *
     * @param   Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Method to share specified services across application.
     *
     * @return  void
     */
    public function bindServices()
    {
        foreach ($this->getRestServices() as $service) {
            /**
             * Lambda callback function to create new REST service.
             *
             * @return  \App\Services\Interfaces\Rest
             */
            $share = function() use ($service) {
                return new $service->class($this->app['db'], $this->app['orm.em'], $this->app['validator']);
            };

            // Register and share service to whole application
            $this->app[$service->name] = $this->app->share($share);
        }
    }

    /**
     * Helper method to get all REST services.
     *
     * @todo Build a cache for this!
     *
     * @return  array
     */
    protected function getRestServices()
    {
        /**
         * Lambda function to get all service classes that exists in application.
         *
         * @param   string  $file
         *
         * @return  null|\stdClass
         */
        $iterator = function($file) {
            // Specify service class name with namespace
            $className = '\\App\\Services\\' . str_replace('.php', '', basename($file));

            // Get reflection about controller class
            $reflectionClass = new \ReflectionClass($className);

            if (!$reflectionClass->isAbstract() &&
                $reflectionClass->implementsInterface('\\App\\Services\\Interfaces\\Rest')
            ) {
                $bits = explode('\\', $reflectionClass->getName());

                // Create output
                $output = new \stdClass();
                $output->class = $reflectionClass->getName();
                $output->name = 'service.' . end($bits);

                return $output;
            }

            return null;
        };

        return array_filter(array_map($iterator, glob($this->app->getRootDir() . 'src/App/Services/*.php')));
    }
}
