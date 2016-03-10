<?php
/**
 * /src/App/Controllers/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers\Interfaces;

// Symfony components
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return  JsonResponse
     */
    public function find();

    /**
     * Returns single entity object presenting given id value.
     *
     * @param   integer $id Entity id
     *
     * @return  JsonResponse
     */
    public function findOne($id);

    /**
     * Creates new entity object to the database.
     *
     * @param   Request $request
     *
     * @return  JsonResponse
     */
    public function create(Request $request);

    /**
     * Updates specified entity data to database.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  JsonResponse
     */
    public function update(Request $request, $id);

    /**
     * Method to delete specified entity from database.
     *
     * @param   integer $id
     *
     * @return  JsonResponse
     */
    public function delete($id);
}
