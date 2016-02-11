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
 * @SWG\Definition(
 *      title="Login data",
 *      description="JSON object for login",
 *      type="object",
 *      required={
 *          "identifier",
 *          "password"
 *      },
 *      example={
 *          "identifier": "user identifier",
 *          "password": "user password",
 *      },
 *  )
 *
 * @category    Model
 * @package     App\Models
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Login
{
    /**
     * User identifier; email or username
     *
     * @var string
     *
     * @SWG\Property()
     */
    private $identifier;

    /**
     * User password.
     *
     * @var string
     *
     * @SWG\Property()
     */
    private $password;

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

    /**
     * Getter method for 'identifier' attribute.
     *
     * @return  string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Getter method for 'password' attribute.
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setter method for 'identifier' attribute.
     *
     * @param   string  $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Setter method for 'password' attribute.
     *
     * @param   string  $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
