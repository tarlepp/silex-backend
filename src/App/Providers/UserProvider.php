<?php

namespace App\Providers;

use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use App\Models\User;

class UserProvider implements UserProviderInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * UserProvider constructor.
     *
     * @param   Connection  $connection
     *
     * @return  UserProvider
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        /* This is implemented in next phase
        $sql = "SELECT * FROM user WHERE username = ?";
        $user = $this->connection->fetchAssoc($sql, array((string) $username));

        die(__FILE__ . ":" . __LINE__);

        throw new UsernameNotFoundException('foo');
        */

        // Create 'dummy' user that is always available.
        $user = new User;
        $user->username = $username;

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
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
    }
}