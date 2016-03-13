<?php
/**
 * /src/App/Controllers/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers\Interfaces;

// Application components
use App\Entities\Base as Entity;

// Symfony components
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface Rest
 *
 * Interface definition for all application REST controller classes.
 *
 * @category    Interface
 * @package     App\Controllers\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Rest
{
    /**
     * Method to expose necessary services for controller use.
     *
     * @return  void
     */
    public function exposeServices();

    /**
     * Returns all specified entities from database as an array of entity objects.
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function find(Request $request);

    /**
     * Returns single entity object presenting given id value.
     *
     * @param   Request $request    Request object
     * @param   integer $id         Entity id
     *
     * @return  Response
     */
    public function findOne(Request $request, $id);

    /**
     * Creates new entity object to the database.
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request);

    /**
     * Updates specified entity data to database.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function update(Request $request, $id);

    /**
     * Method to delete specified entity from database.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function delete(Request $request, $id);

    /**
     * Before lifecycle method for find method.
     *
     * @param   Request         $request
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     */
    public function beforeFind(
        Request $request,
        array &$criteria = [],
        array &$orderBy = null,
        &$limit = null,
        &$offset = null
    );

    /**
     * After lifecycle method for find method.
     *
     * @param   Request         $request
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     * @param   Entity[]        $entities
     */
    public function afterFind(
        Request $request,
        array &$criteria = [],
        array &$orderBy = null,
        &$limit = null,
        &$offset = null,
        array &$entities
    );

    /**
     * Before lifecycle method for findOne method.
     *
     * @param   Request $request
     * @param   integer $id
     */
    public function beforeFindOne(Request $request, &$id);

    /**
     * After lifecycle method for findOne method.
     *
     * @param   Request $request
     * @param   integer $id
     * @param   Entity  $entity
     */
    public function afterFindOne(Request $request, &$id, Entity $entity);

    /**
     * Before lifecycle method for create method.
     *
     * @param   Request     $request
     * @param   \stdClass   $data
     */
    public function beforeCreate(Request $request, \stdClass $data);

    /**
     * After lifecycle method for create method.
     *
     * @param   Request     $request
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function afterCreate(Request $request, \stdClass $data, Entity $entity);

    /**
     * Before lifecycle method for update method.
     *
     * @param   Request     $request
     * @param   integer     $id
     * @param   \stdClass   $data
     */
    public function beforeUpdate(Request $request, &$id, \stdClass $data);

    /**
     * After lifecycle method for update method.
     *
     * @param   Request     $request
     * @param   integer     $id
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function afterUpdate(Request $request, &$id, \stdClass $data, Entity $entity);

    /**
     * Before lifecycle method for delete method.
     *
     * @param   Request $request
     * @param   integer $id
     */
    public function beforeDelete(Request $request, &$id);

    /**
     * After lifecycle method for delete method.
     *
     * @param   Request $request
     * @param   integer $id
     * @param   Entity  $entity
     */
    public function afterDelete(Request $request, &$id, Entity $entity);
}
