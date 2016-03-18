<?php
/**
 * /src/App/Providers/ControllerProvider.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Providers;

// Application components
use App\Controllers;

// Silex components
use Silex\Application as App;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class ControllerProvider
 *
 * @category    Provider
 * @package     App\Providers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ControllerProvider implements ControllerProviderInterface
{
    /**
     * Current Silex application
     *
     * @var App
     */
    private $app;

    /**
     * Returns routes to connect to the given application.
     *
     * @param   App $app    An Application instance
     *
     * @return  ControllerCollection    A ControllerCollection instance
     */
    public function connect(App $app)
    {
        // Store application
        $this->app = $app;

        // Set error handling globally
        $this->app->error([$this, 'error']);

        /**
         * Get application current controllers
         *
         * @var ControllerCollection $controllers
         */
        $controllers = $this->app['controllers_factory'];

        // Mount controllers to specified routes.
        $this->mount();

        return $controllers;
    }

    /**
     * Generic error handler for application. Note that this will _not_ catch PHP errors, those are handled via
     * App\Core\ExceptionHandler class which extends base Symfony ExceptionHandler class.
     *
     * @param   \Exception  $exception
     * @param   integer     $status
     *
     * @return  \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function error(\Exception $exception, $status)
    {
        // Basic error data
        $error = [
            'message'   => $exception->getMessage(),
            'status'    => $status,
            'code'      => $exception->getCode(),
        ];

        // If we're running application in debug mode, attach some extra information about actual error
        if ($this->app['debug']) {
            $error += [
                'debug' => [
                    'file'          => $exception->getFile(),
                    'line'          => $exception->getLine(),
                    'trace'         => $exception->getTrace(),
                    'traceString'   => $exception->getTraceAsString(),
                ]
            ];
        }

        // And return JSON output
        return $this->app->json($error, $status);
    }

    /**
     * Attach application mount points to specified controllers.
     *
     * @todo    can this be done automatic?
     *
     * @return  void
     */
    private function mount()
    {
        $this->app->mount('/', new Controllers\IndexController());
        $this->app->mount('/auth', new Controllers\AuthController());
        $this->app->mount('/author', new Controllers\AuthorController());
    }
}
