<?php
/**
 * /src/App/Controllers/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

/**
 * Class Base
 *
 * Abstract base class for all application controllers.
 *
 * @category    Controller
 * @package     App\Controllers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base implements ControllerProviderInterface, Interfaces\Base
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ControllerCollection
     */
    protected $controllers;

    /**
     * Returns routes to connect to the given application.
     *
     * @param   Application     $app    An Application instance
     *
     * @return  ControllerCollection    A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        // Store Application and ControllerCollection collection to class context
        $this->app = $app;
        $this->controllers = $app['controllers_factory'];

        // Register current controller routes
        $this->registerRoutes();

        return $this->controllers;
    }
}