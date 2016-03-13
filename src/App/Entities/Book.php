<?php
/**
 * /src/App/Entities/Book.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

// Symfony components
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

// Doctrine components
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class Book
 *
 * @SWG\Definition(
 *      title="Book",
 *      description="Book data as in JSON object",
 *      type="object",
 *      required={
 *          "title",
 *          "description",
 *          "releaseDate",
 *          "author"
 *      },
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
class Book extends Base
{
    // Traits
    use ORMBehaviors\Blameable\Blameable;
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * Book ID
     *
     * @var integer
     *
     * @SWG\Property()
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false
     *  )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Book title
     *
     * @var string
     *
     * @SWG\Property()
     * @ORM\Column(
     *      name="title",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $title;

    /**
     * Description
     *
     * @var string
     *
     * @SWG\Property()
     * @ORM\Column(
     *      name="description",
     *      type="text",
     *      nullable=false
     *  )
     */
    private $description;

    /**
     * Release date of book
     *
     * @var \DateTime
     *
     * @SWG\Property()
     * @ORM\Column(
     *      name="releaseDate",
     *      type="date",
     *      nullable=false
     *  )
     */
    private $releaseDate;

    /**
     * @var \App\Entities\Author
     *
     * @SWG\Property()
     * @ORM\ManyToOne(targetEntity="App\Entities\Author")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="author",
     *          referencedColumnName="id"
     *      )
     *  })
     */
    private $author;

    /**
     * Validator definitions for 'Book' entity
     *
     * @param   ClassMetadata   $metadata
     *
     * @return  void
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('releaseDate', new Assert\NotBlank());
        $metadata->addPropertyConstraint('releaseDate', new Assert\Date());
        $metadata->addPropertyConstraint('author', new Assert\NotBlank());
    }

    /**
     * Getter method for 'id' attribute.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter method for 'title' attribute.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Getter method for 'description' attribute.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Getter method for 'releaseDate' attribute.
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Getter method for 'author' attribute.
     *
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
