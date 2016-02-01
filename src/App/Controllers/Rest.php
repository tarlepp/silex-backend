<?php
/**
 * /src/App/Controllers/RestController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

use Silex\Application;

/**
 * Abstract class that all basic REST controllers uses.
 *
 * @category    Controller
 * @package     App\Controllers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Rest extends Base implements Interfaces\Rest
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param   Application $app
     *
     * @return  \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        parent::connect($app);

        $this->exposeServices();

        return $this->controllers;
    }
}
