<?php
/**
 * /src/App/Entities/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

// Doctrine components
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class Base
 *
 * Abstract base class to all application entities.
 *
 * @category    Model
 * @package     App\Entities
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base
{
    // Traits
    use ORMBehaviors\Blameable\Blameable;
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * Created at datetime.
     *
     * @var null|\DateTime
     *
     * @JMS\Accessor(getter="getCreatedAtJson")
     * @JMS\Groups({"default"})
     *
     * @SWG\Property()
     * @ORM\Column(
     *      name="createdAt",
     *      type="datetime",
     *      nullable=true,
     *  )
     */
    protected $createdAt;

    /**
     * Created user
     *
     * @var null|\App\Entities\User
     *
     * @SWG\Property()
     * @JMS\Groups({"createdBy"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="createdBy_id",
     *          referencedColumnName="id",
     *          nullable=true,
     *      ),
     *  })
     */
    protected $createdBy;

    /**
     * Updated at datetime.
     *
     * @var null|\DateTime
     *
     * @JMS\Accessor(getter="getUpdatedAtJson")
     * @JMS\Groups({"default"})
     *
     * @SWG\Property()
     * @ORM\Column(
     *      name="updatedAt",
     *      type="datetime",
     *      nullable=true,
     *  )
     */
    protected $updatedAt;

    /**
     * Updated user
     *
     * @var null|\App\Entities\User
     *
     * @SWG\Property()
     * @JMS\Groups({"updatedBy"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="updatedBy_id",
     *          referencedColumnName="id",
     *          nullable=true,
     *      ),
     *  })
     */
    protected $updatedBy;

    /**
     * Getter for createdAt
     *
     * @return  \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Getter for createdBy
     *
     * @return  User|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Getter for updatedAt
     *
     * @return  \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Getter for updatedBy
     *
     * @return  User|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Getter method for 'createdAt' attribute for JSON output.
     *
     * @return string
     */
    public function getCreatedAtJson()
    {
        return $this->formatDatetime($this->getCreatedAt());
    }

    /**
     * Getter method for 'updatedAt' attribute for JSON output.
     *
     * @return string
     */
    public function getUpdatedAtJson()
    {
        return $this->formatDatetime($this->getUpdatedAt());
    }

    /**
     * Setter for createdAt
     *
     * @param   \DateTime|null  $createdAt
     *
     * @return  Base
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Setter for createdBy
     *
     * @param   User|null   $createdBy
     *
     * @return  Base
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Setter for updatedAt
     *
     * @param   \DateTime|null  $updatedAt
     *
     * @return  Base
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Setter for updatedBy
     *
     * @param   User|null   $updatedBy
     *
     * @return  Base
     */
    public function setUpdatedBy(User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Helper method to format given \DateTime object to RFC3339 format.
     *
     * @see https://www.ietf.org/rfc/rfc3339.txt
     *
     * @param   \DateTime|null  $dateTime
     *
     * @return  null|string
     */
    protected function formatDatetime(\DateTime $dateTime = null)
    {
        return is_null($dateTime) ? null : $dateTime->format(\DATE_RFC3339);
    }
}
