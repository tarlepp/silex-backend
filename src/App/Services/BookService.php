<?php
/**
 * /src/App/Services/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Entity components
use App\Entities\Book as Entity;

/**
 * Class AuthorService
 *
 * @category    Services
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Entity          getReference($entityName, $id)
 * @method  Entity[]        find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
 * @method  null|Entity     findOne($id)
 * @method  Entity          create(\stdClass $data)
 * @method  Entity          update($id, \stdClass $data)
 * @method  Entity          delete($id)
 */
class BookService extends Rest
{
    /**
     * Name of the repository that current REST API will use.
     *
     * @var string
     */
    public $repositoryName = 'App\Entities\Book';
}
