<?php
/**
 * /src/App/Controllers/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

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
}
