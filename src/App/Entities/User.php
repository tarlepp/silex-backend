<?php
/**
 * /src/App/Entities/User.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

// Symfony components
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

// Doctrine components
use Doctrine\ORM\Mapping as ORM;

// 3rd party components
use Swagger\Annotations as SWG;

/**
 * User
 *
 * @SWG\Model(
 *     id="UserEntity",
 *     description="User object"
 * )
 *
 * @ORM\Table(
 *      name="user",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uq_username", columns={"username"}),
 *          @ORM\UniqueConstraint(name="uq_email", columns={"email"})
 *      }
 *  )
 * @ORM\Entity
 */
class User extends Base implements AdvancedUserInterface, \Serializable
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
     *      name="username",
     *      type="string",
     *      description="Username"
     *  )
     *
     * @ORM\Column(
     *      name="username",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $username;

    /**
     * @var string
     *
     * @SWG\Property(
     *      name="firstname",
     *      type="string",
     *      description="Firstname"
     *  )
     *
     * @ORM\Column(
     *      name="firstname",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $firstname;

    /**
     * @var string
     *
     * @SWG\Property(
     *      name="surname",
     *      type="string",
     *      description="Surname"
     *  )
     *
     * @ORM\Column(
     *      name="surname",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $surname;

    /**
     * @var string
     *
     * @SWG\Property(
     *      name="email",
     *      type="string",
     *      description="Email address"
     *  )
     *
     * @ORM\Column(
     *      name="email",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $email;

    /**
     * @var string
     *
     * @ORM\Column(
     *      name="password",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    private $password;

    /**
     * @var string
     *
     * @SWG\Property(
     *      name="roles",
     *      description="User roles",
     *      type="array",
     *      enum="['ROLE_ADMIN','ROLE_USER']"
     *  )
     *
     * @ORM\Column(
     *      name="roles",
     *      type="string",
     *      length=255,
     *      nullable=false
     *  )
     */
    public $roles;

    /**
     * Getter method for current user ID.
     *
     * @return  integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        if (!is_array($this->roles)) {
            $this->roles = explode(',', $this->roles);
        }

        $roles = $this->roles;

        // Every user must have at least one role, per Silex security docs.
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Method to verify given password against hashed one.
     *
     * @param   string  $password
     *
     * @return  bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        // TODO: Implement isAccountNonExpired() method.
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        // TODO: Implement isAccountNonLocked() method.
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        // TODO: Implement isCredentialsNonExpired() method.
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        return true;
    }

    /**
     * String representation of object
     *
     * @link    http://php.net/manual/en/serializable.serialize.php
     *
     * @return  string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize([
            $this->id,
        ]);
    }

    /**
     * Constructs the object
     *
     * @link    http://php.net/manual/en/serializable.unserialize.php
     *
     * @param   string $serialized The string representation of the object.
     *
     * @return  void
     */
    public function unserialize($serialized)
    {
        list($this->id) = unserialize($serialized);
    }

    /**
     * Getter method for user identifier, this can be username or email.
     *
     * @todo    How to determine which one this is?
     *
     * @return  string
     */
    public function getIdentifier()
    {
        return $this->username;
    }
}
