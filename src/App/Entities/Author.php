<?php
/**
 * /src/App/Entities/Author.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

// Application components
use App\Doctrine\Behaviours as ORMBehaviors;

// Symfony components
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

// Doctrine components
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Author
 *
 * @SWG\Definition(
 *      title="Author",
 *      description="Author data as in JSON object",
 *      type="object",
 *      required={
 *          "name",
 *          "description",
 *      },
 *      example={
 *          "id": 1,
 *          "name": "J. R. R. Tolkien",
 *          "description": "John Ronald Reuel Tolkien, CBE (/ˈtɒlkiːn/ tol-keen; 3 January 1892 – 2 September 1973)",
 *      },
 *  )
 *
 * @ORM\Table(
 *      name="author"
 *  )
 * @ORM\Entity(
 *      repositoryClass="App\Repositories\Author"
 *  )
 *
 * @category    Entity
 * @package     App\Entities
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Author extends Base
{
    // Traits
    use ORMBehaviors\Blameable;
    use ORMBehaviors\Timestampable;

    /**
     * Author ID
     *
     * @var integer
     *
     * @SWG\Property()
     * @JMS\Groups({"Default", "Author", "AuthorId"})
     *
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
     * Author name
     *
     * @var string
     *
     * @SWG\Property()
     * @JMS\Groups({"Default", "Author"})
     *
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $name;

    /**
     * Description
     *
     * @var string
     *
     * @SWG\Property()
     * @JMS\Groups({"Default", "Author"})
     *
     * @ORM\Column(
     *      name="description",
     *      type="text",
     *      nullable=false
     *  )
     */
    private $description;

    /**
     * Author books
     *
     * @var \App\Entities\Book[]
     *
     * @SWG\Property()
     * @JMS\Groups({"Books"})
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entities\Book",
     *      mappedBy="author"
     *  )
     */
    private $books;

    /**
     * Validator definitions for 'Author' entity
     *
     * @param   ClassMetadata   $metadata
     *
     * @return  void
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('description', new Assert\NotBlank());
    }

    /**
     * Getter for 'id' attribute.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for 'name' attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for 'description' attribute.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Getter for author books.
     *
     * @return  Book[]
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Setter for 'name' attribute.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Setter for 'description' attribute.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
