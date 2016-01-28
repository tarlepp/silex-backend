<?php
/**
 * /src/App/Providers/UserProvider.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Providers;

// Doctrine specified dependencies
use Doctrine\ORM\EntityManager;

// Symfony specified dependencies
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

// Application specified dependencies
use App\Entities\User;

/**
 * Class UserProvider
 *
 * @category    Provider
 * @package     App\Providers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserProvider constructor.
     *
     * @param   EntityManager   $entityManager
     *
     * @return  UserProvider
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not found.
     *
     * @throws  UsernameNotFoundException if the user is not found
     *
     * @param   string  $username   The username
     *
     * @return  User
     */
    public function loadUserByUsername($username)
    {
        // Fetch user
        $user = $this->entityManager
            ->getRepository('App\Entities\User')
            ->findOneBy(['username' => $username]);

        if (is_null($user)) {
            throw new UsernameNotFoundException(
                sprintf(
                    'User \'%s\' not found.',
                    $username
                )
            );
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @throws  UnsupportedUserException if the account is not supported
     *
     * @param   UserInterface|User  $user
     *
     * @return  User
     */
    public function refreshUser(UserInterface $user)
    {
        // Check if given UserInterface object is supported
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    get_class($user)
                )
            );
        }

        return $this->getUser($user->getId());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param   string  $class
     *
     * @return  bool
     */
    public function supportsClass($class)
    {
        return ($class === 'App\Entities\User') || is_subclass_of($class, 'App\Entities\User');
    }

    /**
     * Getter method for user entity.
     *
     * @throws  \Doctrine\ORM\ORMException
     * @throws  \Doctrine\ORM\OptimisticLockException
     * @throws  \Doctrine\ORM\TransactionRequiredException
     *
     * @param   integer $id User id
     *
     * @return  null|User
     */
    public function getUser($id)
    {
        return $this->entityManager
            ->find('App\Entities\User', $id);
    }
}
