<?php
/**
 * /src/App/Entities/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

// Doctrine components
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use Swagger\Annotations as SWG;

/**
 * Class Author
 *
 * @SWG\Model(
 *     id="AuthorEntity",
 *     description="Author object"
 * )
 *
 * @ORM\Table(
 *      name="author"
 *  )
 * @ORM\Entity
 *
 * @category    Entity
 * @package     App\Entities
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Author extends Base
{
    /**
     * @var integer
     *
     * @SWG\Property(
     *      name="id",
     *      type="integer",
     *      description="ID"
     *  )
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false
     *  )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string
     *
     * @SWG\Property(
     *      name="name",
     *      type="integer",
     *      description="Name"
     *  )
     *
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $name;

    /**
     * @var string
     *
     * @SWG\Property(
     *      name="description",
     *      type="string",
     *      description="Description"
     *  )
     *
     * @ORM\Column(
     *      name="description",
     *      type="text",
     *      nullable=false
     *  )
     */
    public $description;
}
