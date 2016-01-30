<?php
/**
 * /src/App/Controllers/IndexController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

/**
 * Class IndexController
 *
 * This handles following route handling on application:
 *  GET /
 *
 * @category    Controller
 * @package     App\Controllers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class IndexController extends Base
{
    /**
     * Method to register all routes for current controller.
     *
     * @return void
     */
    public function registerRoutes()
    {
        $this->app->get('/', [$this, 'index']);
    }

    /**
     * Index action handling, this will just redirect user to API docs.
     *
     * @return string
     */
    public function index()
    {
        return $this->app->redirect('api/api-docs');
    }
}
