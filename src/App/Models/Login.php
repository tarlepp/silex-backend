<?php
/**
 * /src/App/Models/Login.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Models;

// Symfony components
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

// 3rd party components
use Swagger\Annotations as SWG;

/**
 * Class Login
 *
 * Model class for login process, this is needed to just validate user input.
 *
 * @SWG\Model(
 *     id="Login",
 *     description="JSON object for login"
 * )
 *
 * @category    Model
 * @package     App\Models
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Login
{
    /**
     * @SWG\Property(
     *      name="identifier",
     *      type="string",
     *      description="User identifier; email or username"
     *  )
     *
     * @var string
     */
    public $identifier;

    /**
     *  @SWG\Property(
     *      name="password",
     *      type="string",
     *      description="User password"
     *  )
     *
     * @var string
     */
    public $password;

    /**
     * Validator definitions for 'login' model
     *
     * @param   ClassMetadata   $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('identifier', new Assert\NotBlank());
        $metadata->addPropertyConstraint('password', new Assert\NotBlank());
    }
}
