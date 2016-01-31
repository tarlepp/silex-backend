<?php
/**
 * /src/App/Services/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Interfaces;

use App\Entities\Base as BaseEntity;

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
 * @category    Services
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
     * @return array
     */
    public function find();

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     *
     * @param   integer $id
     *
     * @return  null|object
     */
    public function findOne($id);

    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws  ValidatorException
     *
     * @param   array|\stdClass $data
     *
     * @return  BaseEntity
     */
    public function create($data);

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws  HttpException
     * @throws  ValidatorException
     *
     * @param   integer         $id
     * @param   array|\stdClass $data
     *
     * @return  BaseEntity
     */
    public function update($id, $data);

    /**
     * Generic method to delete specified entity from database.
     *
     * @param   integer $id
     *
     * @return  BaseEntity
     */
    public function delete($id);
}
