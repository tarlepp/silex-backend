<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160125223642 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $user = $schema->createTable('user');
        $user->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $user->addColumn('username', 'string', ['length' => 255]);
        $user->addColumn('firstname', 'string', ['length' => 255]);
        $user->addColumn('surname', 'string', ['length' => 255]);
        $user->addColumn('email', 'string', ['length' => 255]);
        $user->addColumn('password', 'string', ['length' => 255]);
        $user->addColumn('roles', 'string', ['length' => 255]);
        $user->setPrimaryKey(['id'], 'pk_id');
        $user->addUniqueIndex(['username'], 'uq_username');
        $user->addUniqueIndex(['email'], 'uq_email');
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function postUp(Schema $schema)
    {
        // Data to add
        $data = [
            'username'      => 'admin',
            'firstname'     => 'Arnold',
            'surname'       => 'Administrator',
            'email'         => 'arnold@foobar.com',
            'password'      =>  password_hash('nimda', \PASSWORD_DEFAULT, ['cost' => 12]),
            'roles'         => 'ROLE_ADMIN',
        ];

        $this->connection->insert('user', $data);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('user');
    }
}
