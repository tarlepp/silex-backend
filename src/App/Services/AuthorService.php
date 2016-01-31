<?php
/**
 * /src/App/Services/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Doctrine components
use Doctrine\ORM\EntityRepository;

// Entity components
use App\Entities\Author;

/**
 * Class AuthorService
 *
 * @category    Services
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Author[]    find()
 * @method  Author      findOne($id)
 * @method  Author      create($data)
 * @method  Author      update($id, $data)
 * @method  Author      delete($id)
 */
class AuthorService extends Base
{
    /**
     * Getter method for current repository.
     *
     * @todo    this should be done other way...
     *
     * @return  EntityRepository
     */
    public function getRepository()
    {
        if (!$this->repository) {
            $this->repository = $this->entityManager->getRepository('App\Entities\Author');
        }

        return $this->repository;
    }
}
