<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160313144811 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE `author` ADD `createdAt` DATETIME NULL, ADD `updatedAt` DATETIME NULL;');
        $this->addSql('ALTER TABLE `book` ADD `createdAt` DATETIME NULL, ADD `updatedAt` DATETIME NULL;');
        $this->addSql('ALTER TABLE `user` ADD `createdAt` DATETIME NULL, ADD `updatedAt` DATETIME NULL;');

        $this->addSql('ALTER TABLE `author` ADD `createdBy_id` INT NULL AFTER `createdAt`, ADD INDEX (`createdBy_id`);');
        $this->addSql('ALTER TABLE `author` ADD `updatedBy_id` INT NULL AFTER `updatedAt`, ADD INDEX (`updatedBy_id`);');
        $this->addSql('ALTER TABLE `author` ADD `deletedBy_id` INT NULL, ADD INDEX (`deletedBy_id`);');

        $this->addSql('ALTER TABLE `book` ADD `createdBy_id` INT NULL AFTER `createdAt`, ADD INDEX (`createdBy_id`);');
        $this->addSql('ALTER TABLE `book` ADD `updatedBy_id` INT NULL AFTER `updatedAt`, ADD INDEX (`updatedBy_id`);');
        $this->addSql('ALTER TABLE `book` ADD `deletedBy_id` INT NULL, ADD INDEX (`deletedBy_id`);');

        $this->addSql('ALTER TABLE `user` ADD `createdBy_id` INT NULL AFTER `createdAt`, ADD INDEX (`createdBy_id`);');
        $this->addSql('ALTER TABLE `user` ADD `updatedBy_id` INT NULL AFTER `updatedAt`, ADD INDEX (`updatedBy_id`);');
        $this->addSql('ALTER TABLE `user` ADD `deletedBy_id` INT NULL, ADD INDEX (`deletedBy_id`);');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE `author` DROP `createdAt`, DROP `updatedAt`;');
        $this->addSql('ALTER TABLE `book` DROP `createdAt`, DROP `updatedAt`;');
        $this->addSql('ALTER TABLE `user` DROP `createdAt`, DROP `updatedAt`;');

        $this->addSql('ALTER TABLE `author` DROP `createdBy_id`, DROP `updatedBy_id`, DROP `deletedBy_id`;');
        $this->addSql('ALTER TABLE `book` DROP `createdBy_id`, DROP `updatedBy_id`, DROP `deletedBy_id`;');
        $this->addSql('ALTER TABLE `user` DROP `createdBy_id`, DROP `updatedBy_id`, DROP `deletedBy_id`;');
    }
}
