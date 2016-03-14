<?php
/**
 * /src/App/Controllers/IndexController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

// Symfony components
use Symfony\Component\HttpFoundation\Request;

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
        $this->app->get('/test', [$this, 'test']);
    }

    /**
     * Index action handling, this will just redirect user to API docs.
     *
     * @param   Request $request
     *
     * @return  \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(Request $request)
    {
        return $this->app->redirect($request->getBasePath() . '/api-docs');
    }

    /**
     * This is just for testing what ever you might wanna test. Note that this route is public so don't ever commit
     * your changes to this :D
     *
     * @param Request $request
     *
     * @return string
     */
    public function test(Request $request)
    {
        $criteria = $request->get('criteria', []);
        $orderBy = $request->get('orderBy', null);
        $limit = $request->get('limit', null);
        $offset = $request->get('offset', null);


        $data = $this->app['author.service']->find($criteria, $orderBy, $limit, $offset);


        return $this->app["serializer"]->serialize($data, 'json');
    }
}
