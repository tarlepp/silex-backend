<?php
/**
 * /src/App/Models/Login.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Models;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Login
 *
 * Model class for login process, this is needed to just validate user input.
 *
 * @category    Model
 * @package     App\Models
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Login
{
    /**
     * @var string
     */
    public $identifier;

    /**
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
