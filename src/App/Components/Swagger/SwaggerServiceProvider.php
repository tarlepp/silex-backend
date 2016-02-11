<?php
/**
 * /src/App/Components/Swagger/SwaggerServiceProvider.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Components\Swagger;

// Silex components
use Silex\Application;
use Silex\ServiceProviderInterface;

// Doctrine components
use Doctrine\Common\Annotations\AnnotationRegistry;

// Swagger components
use Swagger\Logger;

/**
 * Class SwaggerServiceProvider
 *
 * The SwaggerServiceProvider adds a swagger-php service to a silex app. It also adds the routes necessary for
 * integrating with swagger-ui.
 *
 * @category    Provider
 * @package     App\Components\Swagger
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SwaggerServiceProvider implements ServiceProviderInterface
{
    /**
     * Add routes to the app that generate swagger documentation based on your annotations
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        // Attach logger
        if ($app['logger']) {
            $logger = Logger::getInstance();

            $originalLog = $logger->log;

            $logger->log = function ($entry, $type) use ($app, $originalLog) {
                $app['logger']->notice($entry);

                $originalLog($entry, $type);
            };
        }

        // Register route for docs
        $app->get('/api-docs', new SwaggerController());
    }

    /**
     * Registers the swagger service.
     *
     * @param   Application $app
     *
     * @return  void
     */
    public function register(Application $app)
    {
        // Get swagger options
        $swaggerOptions = $app['vars']->get('swagger');

        // Swagger annotation file pattern
        $pattern = sprintf(
            "%1\$s%2\$sAnnotations%2\$s*.php",
            $swaggerOptions['srcDir'],
            DIRECTORY_SEPARATOR
        );

        /**
         * Register each Swagger annotation file, this should work via AnnotationRegistry::registerAutoloadNamespace(),
         * but for some reason it just won't work...
         *
         * Try to figure this out.
         */
        foreach(glob($pattern) as $file) {
            AnnotationRegistry::registerFile($file);
        }
    }
}
