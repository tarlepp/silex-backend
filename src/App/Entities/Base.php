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
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="createdBy_id",
     *          referencedColumnName="id",
     *          nullable=true
     *      ),
     *  })
     */
    protected $createdBy;

    /**
     * Updated at datetime.
     *
     * @var null|\DateTime
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
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(
     *          name="createdBy_id",
     *          referencedColumnName="id",
     *          nullable=true
     *      ),
     *  })
     */
    protected $updatedBy;
}
