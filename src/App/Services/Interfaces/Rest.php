<?php
/**
 * /src/App/Services/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Interfaces;

// Application components
use App\Entities\Base as Entity;

// Doctrine components
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

// Symfony components
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Interface for REST based services.
 *
 * @category    Interfaces
 * @package     App\Services\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Rest
{
    /**
     * Class constructor.
     *
     * @param   Connection          $db
     * @param   EntityManager       $entityManager
     * @param   RecursiveValidator  $validator
     */
    public function __construct(Connection $db, EntityManager $entityManager, RecursiveValidator $validator);

    /**
     * Getter method for current repository.
     *
     * @param   bool    $force  Force entity manager fetch
     *
     * @return  EntityRepository
     */
    public function getRepository($force = false);

    /**
     * Generic find method to return an array of items from database. Return value is an array of specified repository
     * entities.
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     *
     * @return  Entity[]
     */
    public function find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null);

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     *
     * @param   integer $id
     *
     * @return  null|Entity
     */
    public function findOne($id);

    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws  ValidatorException
     *
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function create(\stdClass $data);

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws  HttpException
     * @throws  ValidatorException
     *
     * @param   integer     $id
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function update($id, \stdClass $data);

    /**
     * Generic method to delete specified entity from database.
     *
     * @param   integer $id
     *
     * @return  Entity
     */
    public function delete($id);

    /**
     * Before lifecycle method for find method.
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     */
    public function beforeFind(array &$criteria = [], array &$orderBy = null, &$limit = null, &$offset = null);

    /**
     * After lifecycle method for find method.
     *
     * @param   Entity[]     $data
     * @param   array        $criteria
     * @param   null|array   $orderBy
     * @param   null|integer $limit
     * @param   null|integer $offset
     */
    public function afterFind(
        &$data = [],
        array &$criteria = [],
        array &$orderBy = null,
        &$limit = null,
        &$offset = null
    );

    /**
     * Before lifecycle method for findOne method.
     *
     * @param   integer $id
     */
    public function beforeFindOne($id);

    /**
     * After lifecycle method for findOne method.
     *
     * @param   null|\stdClass|Entity   $data
     * @param   integer                 $id
     */
    public function afterFindOne(&$data, $id);

    /**
     * Before lifecycle method for create method.
     *
     * @param   Entity      $entity
     * @param   \stdClass   $data
     */
    public function beforeCreate(Entity $entity, \stdClass $data);

    /**
     * After lifecycle method for create method.
     *
     * @param   Entity      $entity
     * @param   \stdClass   $data
     */
    public function afterCreate(Entity $entity, \stdClass $data);

    /**
     * Before lifecycle method for update method.
     *
     * @param   Entity      $entity
     * @param   \stdClass   $data
     */
    public function beforeUpdate(Entity $entity, \stdClass $data);

    /**
     * After lifecycle method for update method.
     *
     * @param   Entity      $entity
     * @param   \stdClass   $data
     */
    public function afterUpdate(Entity $entity, \stdClass $data);

    /**
     * Before lifecycle method for delete method.
     *
     * @param   Entity  $entity
     * @param   integer $id
     */
    public function beforeDelete(Entity $entity, $id);

    /**
     * After lifecycle method for delete method.
     *
     * @param   Entity  $entity
     * @param   integer $id
     */
    public function afterDelete(Entity $entity, $id);
}
