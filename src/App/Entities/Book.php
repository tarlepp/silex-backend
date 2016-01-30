<?php
/**
 * /src/App/Entities/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Book
 *
 * @SWG\Model(
 *     id="BookEntity",
 *     description="Book object"
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
class Book
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
     *      name="title",
     *      type="integer",
     *      description="Title"
     *  )
     *
     * @ORM\Column(
     *      name="title",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $title;

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

    /**
     * @var \DateTime
     *
     * @SWG\Property(
     *      name="releaseDate",
     *      type="string",
     *      description="Release date"
     *  )
     *
     * @ORM\Column(
     *      name="releaseDate",
     *      type="date",
     *      nullable=false
     *  )
     */
    public $releasedate;

    /**
     * @var \App\Entities\Author
     *
     * @SWG\Property(
     *      name="author",
     *      type="AuthorEntity",
     *      description="Author"
     *  )
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Author")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="author",
     *          referencedColumnName="id"
     *      )
     *  })
     */
    public $author;
}
