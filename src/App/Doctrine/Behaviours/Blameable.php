<?php
/**
 * /src/App/Doctrine/Behaviours/Blameable.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Doctrine\Behaviours;

use Knp\DoctrineBehaviors\Model\Blameable\BlameableMethods;

/**
 * Blameable trait.
 *
 * Should be used inside entity where you need to track which user created or updated it.
 * 
 * Note that this uses KnpLabs/DoctrineBehaviors (https://github.com/KnpLabs/DoctrineBehaviors) and we just need to
 * override property definitions and add some custom functions to it. This is needed for to JMS serializer to work as
 * it should.
 *
 * @category    Doctrine
 * @package     App\Doctrine\Behaviours
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Blameable
{
    use BlameableMethods;

    /**
     * Created user
     *
     * @var null|\App\Entities\User
     *
     * @SWG\Property()
     * @JMS\Groups({"CreatedBy"})
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
     * Updated user
     *
     * @var null|\App\Entities\User
     *
     * @SWG\Property()
     * @JMS\Groups({"UpdatedBy"})
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
     * Will be mapped to either string or user entity by BlameableSubscriber
     *
     * Note this is not used atm.
     */
    protected $deletedBy;
}
