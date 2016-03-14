<?php
/**
 * /src/App/Controllers/RestController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

// Application components
use App\Services\Rest as RestService;
use App\Entities\Base as Entity;

// Silex components
use Silex\Application;

// Symfony components
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param   Request $request
     *
     * @return  Response
     */
    public function find(Request $request)
    {
        $criteria = $request->get('criteria', []);
        $orderBy = $request->get('orderBy', null);
        $limit = $request->get('limit', null);
        $offset = $request->get('offset', null);

        if (method_exists($this, 'beforeFind')) {
            $this->beforeFind($request, $criteria, $orderBy, $limit, $offset);
        }

        $entities = $this->service->find($criteria, $orderBy, $limit, $offset);

        if (method_exists($this, 'afterFind')) {
            $this->afterFind($request, $criteria, $orderBy, $limit, $offset, $entities);
        }

        return $this->makeResponse($entities);
    }

    /**
     * Returns single entity object presenting given id value.
     *
     * @param   Request $request    Request object
     * @param   integer $id         Entity id
     *
     * @return  Response
     */
    public function findOne(Request $request, $id)
    {
        if (method_exists($this, 'beforeFindOne')) {
            $this->beforeFindOne($request, $id);
        }

        $entity = $this->service->findOne($id);

        if (method_exists($this, 'afterFindOne')) {
            $this->afterFindOne($request, $id, $entity);
        }

        if (is_null($entity)) {
            throw new HttpException(404, 'Not found');
        }

        return $this->makeResponse($entity);
    }

    /**
     * Creates new entity object to the database.
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent());

        try {
            if (method_exists($this, 'beforeCreate')) {
                $this->beforeCreate($request, $data);
            }

            $entity = $this->service->create($data);

            if (method_exists($this, 'afterCreate')) {
                $this->afterCreate($request, $data, $entity);
            }
        } catch (ValidatorException $error) {
            throw new HttpException(400, $error->getMessage());
        }

        return $this->makeResponse($entity, 201);
    }

    /**
     * Updates specified entity data to database.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());

        try {
            if (method_exists($this, 'beforeUpdate')) {
                $this->beforeUpdate($request, $id, $data);
            }

            $entity = $this->service->update($id, $data);

            if (method_exists($this, 'afterUpdate')) {
                $this->afterUpdate($request, $id, $data, $entity);
            }
        } catch (ValidatorException $error) {
            throw new HttpException(400, $error->getMessage());
        }

        return $this->makeResponse($entity, 200);
    }

    /**
     * Method to delete specified entity from database.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function delete(Request $request, $id)
    {
        if (method_exists($this, 'beforeDelete')) {
            $this->beforeDelete($request, $id);
        }

        $entity = $this->service->delete($id);

        if (method_exists($this, 'afterDelete')) {
            $this->afterDelete($request, $id, $entity);
        }

        return $this->makeResponse('', 204);
    }

     /**
      * Before lifecycle method for find method.
      *
      * @param   Request        $request
      * @param   array          $criteria
      * @param   null|array     $orderBy
      * @param   null|integer   $limit
      * @param   null|integer   $offset
      */
     public function beforeFind(
         Request $request,
         array &$criteria = [],
         array &$orderBy = null,
         &$limit = null,
         &$offset = null
     ) { }

     /**
      * After lifecycle method for find method.
      *
      * @param   Request        $request
      * @param   array          $criteria
      * @param   null|array     $orderBy
      * @param   null|integer   $limit
      * @param   null|integer   $offset
      * @param   Entity[]       $entities
      */
     public function afterFind(
         Request $request,
         array &$criteria = [],
         array &$orderBy = null,
         &$limit = null,
         &$offset = null,
         array &$entities
     ) { }

     /**
      * Before lifecycle method for findOne method.
      *
      * @param   Request $request
      * @param   integer $id
      */
     public function beforeFindOne(Request $request, &$id) { }

     /**
      * After lifecycle method for findOne method.
      *
      * @param   Request    $request
      * @param   integer    $id
      * @param   Entity     $entity
      */
     public function afterFindOne(Request $request, &$id, Entity $entity) { }

     /**
      * Before lifecycle method for create method.
      *
      * @param   Request   $request
      * @param   \stdClass $data
      */
     public function beforeCreate(Request $request, \stdClass $data) { }

     /**
      * After lifecycle method for create method.
      *
      * @param   Request    $request
      * @param   \stdClass  $data
      * @param   Entity     $entity
      */
     public function afterCreate(Request $request, \stdClass $data, Entity $entity) { }

     /**
      * Before lifecycle method for update method.
      *
      * @param   Request   $request
      * @param   integer   $id
      * @param   \stdClass $data
      */
     public function beforeUpdate(Request $request, &$id, \stdClass $data) { }

     /**
      * After lifecycle method for update method.
      *
      * @param   Request    $request
      * @param   integer    $id
      * @param   \stdClass  $data
      * @param   Entity     $entity
      */
     public function afterUpdate(Request $request, &$id, \stdClass $data, Entity $entity) { }

     /**
      * Before lifecycle method for delete method.
      *
      * @param   Request $request
      * @param   integer $id
      */
     public function beforeDelete(Request $request, &$id) { }

     /**
      * After lifecycle method for delete method.
      *
      * @param   Request    $request
      * @param   integer    $id
      * @param   Entity     $entity
      */
     public function afterDelete(Request $request, &$id, Entity $entity) { }

     /**
      * Helper method to make JSON response.
      *
      * @param   null|string|Entity|Entity[]    $data
      * @param   integer                        $statusCode
      *
      * @return  Response
      */
     protected function makeResponse($data, $statusCode = 200)
     {
         // Create new response
         $response = new Response();
         $response->setContent(
             (empty($data) && is_string($data)) ? '' : $this->app['serializer']->serialize($data, 'json')
         );
         $response->setStatusCode($statusCode);
         $response->headers->set('Content-Type', 'application/json');

         return $response;
     }
 }
