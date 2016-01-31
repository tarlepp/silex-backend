<?php
/**
 * /src/App/Services/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

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
class AuthorService extends Rest
{
    /**
     * Name of the repository that current REST API will use.
     *
     * @var string
     */
    public $repositoryName = 'App\Entities\Author';
}
