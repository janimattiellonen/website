<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140510154108 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(' RENAME TABLE `xi_tag` TO `tag`');
        $this->addSql(' RENAME TABLE `xi_tagging` TO `tagging`');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(' RENAME TABLE `tag` TO `xi_tag`');
        $this->addSql(' RENAME TABLE `tagging` TO `xi_tagging`');
    }
}
