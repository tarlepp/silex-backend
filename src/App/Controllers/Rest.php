<?php
/**
 * /src/App/Controllers/RestController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

// Application components
use App\Services\Rest as RestService;

// Silex components
use Silex\Application;

// Symfony components
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidatorException;

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
     * Service object for current REST controller.
     *
     * @var RestService
     */
    protected $service;

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

    /**
     * Returns all specified entities from database as an array of entity objects.
     *
     * @return  JsonResponse
     */
    public function find()
    {
        return $this->app->json($this->service->find());
    }

    /**
     * Returns single entity object presenting given id value.
     *
     * @param   integer $id Entity id
     *
     * @return  JsonResponse
     */
    public function findOne($id)
    {
        $entity = $this->service->findOne($id);

        if (is_null($entity)) {
            throw new HttpException(404, 'Not found');
        }

        return $this->app->json($entity);
    }

    /**
     * Creates new entity object to the database.
     *
     * @param   Request $request
     *
     * @return  JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent());

        try {
            $entity = $this->service->create($data);
        } catch (ValidatorException $error) {
            throw new HttpException(400, $error->getMessage());
        }

        return $this->app->json($entity, 201);
    }

    /**
     * Updates specified entity data to database.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());

        try {
            $entity = $this->service->update($id, $data);
        } catch (ValidatorException $error) {
            throw new HttpException(400, $error->getMessage());
        }

        return $this->app->json($entity, 200);
    }

    /**
     * Method to delete specified entity from database.
     *
     * @param   integer $id
     *
     * @return  JsonResponse
     */
    public function delete($id)
    {
        $this->service->delete($id);

        return $this->app->json('', 204);
    }
}
