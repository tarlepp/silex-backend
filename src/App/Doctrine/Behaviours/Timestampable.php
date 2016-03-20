<?php
/**
 * /src/App/Doctrine/Behaviours/Timestampable.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Doctrine\Behaviours;

use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethods;

/**
 * Trait Timestampable
 *
 * Should be used inside entity, that needs to be timestamped.
 *
 * Note that this uses KnpLabs/DoctrineBehaviors (https://github.com/KnpLabs/DoctrineBehaviors) and we just need to
 * override property definitions and add some custom functions to it. This is needed for to JMS serializer to work as
 * it should.
 *
 * @category    Doctrine
 * @package     App\Doctrine\Behaviours
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Timestampable
{
    use TimestampableMethods;

    /**
     * Created at datetime.
     *
     * @var null|\DateTime
     *
     * @JMS\Accessor(getter="getCreatedAtJson")
     * @JMS\Groups({"Default", "Author", "Book", "User"})
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
     * Updated at datetime.
     *
     * @var null|\DateTime
     *
     * @JMS\Accessor(getter="getUpdatedAtJson")
     * @JMS\Groups({"Default", "Author", "Book", "User"})
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
