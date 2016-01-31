<?php
/**
 * /src/App/Entities/Author.php
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
     * Setter for 'name' attribute.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Setter for 'description' attribute.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Validator definitions for 'Author' entity
     *
     * @param   ClassMetadata   $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('description', new Assert\NotBlank());
    }
}
