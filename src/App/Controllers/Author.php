<?php
/**
 * /src/App/Controllers/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

// Application components
use App\Services\AuthorService;

// Symfony components
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidatorException;

// 3rd party components
use Swagger\Annotations as SWG;

/**
 * Class Author
 *
 * This handles following route handling on application:
 *  GET     /author/
 *  GET     /author/{id}
 *  POST    /author/
 *  PUT     /author/{id}
 *  DELETE  /author/{id}
 *
 * @mountPoint  /author
 *
 * @category    Controller
 * @package     App\Controllers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Author extends Rest
{
    /**
     * Service that controller is using.
     *
     * @var \App\Services\Author
     */
    protected $service;

    /**
     * Method to expose necessary services for controller use.
     *
     * @return  void
     */
    public function exposeServices()
    {
        $this->service = $this->app['service.Author'];
    }

    /**
     * Method to register all routes for current controller.
     *
     * @return void
     */
    public function registerRoutes()
    {
        $this->controllers->get('/', [$this, 'find']);
        $this->controllers->get('/{id}', [$this, 'findOne']);
        $this->controllers->post('/', [$this, 'create']);
        $this->controllers->put('/{id}', [$this, 'update']);
        $this->controllers->delete('/{id}', [$this, 'delete']);
    }
}
