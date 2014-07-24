<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140724215100 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE xi_filelib_file (id INT AUTO_INCREMENT NOT NULL, folder_id INT NOT NULL, resource_id INT NOT NULL, data LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', fileprofile VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, status INT NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_E8606524D17F50A6 (uuid), INDEX IDX_E8606524162CB942 (folder_id), INDEX IDX_E860652489329D25 (resource_id), UNIQUE INDEX folderid_filename_unique (folder_id, filename), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE xi_filelib_folder (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, data LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', foldername VARCHAR(255) NOT NULL, folderurl VARCHAR(5000) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_A5EA9E8BD17F50A6 (uuid), INDEX IDX_A5EA9E8B727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE xi_filelib_resource (id INT AUTO_INCREMENT NOT NULL, data LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', hash VARCHAR(255) NOT NULL, mimetype VARCHAR(255) NOT NULL, filesize INT NOT NULL, exclusive TINYINT(1) NOT NULL, date_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE File (id INT AUTO_INCREMENT NOT NULL, data LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', fileprofile VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, status INT NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_2CAD992ED17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE xi_filelib_file ADD CONSTRAINT FK_E8606524162CB942 FOREIGN KEY (folder_id) REFERENCES xi_filelib_folder (id)");
        $this->addSql("ALTER TABLE xi_filelib_file ADD CONSTRAINT FK_E860652489329D25 FOREIGN KEY (resource_id) REFERENCES xi_filelib_resource (id)");
        $this->addSql("ALTER TABLE xi_filelib_folder ADD CONSTRAINT FK_A5EA9E8B727ACA70 FOREIGN KEY (parent_id) REFERENCES xi_filelib_folder (id)");
        $this->addSql("ALTER TABLE article ADD published TINYINT(1) DEFAULT NULL");
        $this->addSql('UPDATE article set published = 1 WHERE 1');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE xi_filelib_file DROP FOREIGN KEY FK_E8606524162CB942");
        $this->addSql("ALTER TABLE xi_filelib_folder DROP FOREIGN KEY FK_A5EA9E8B727ACA70");
        $this->addSql("ALTER TABLE xi_filelib_file DROP FOREIGN KEY FK_E860652489329D25");
        $this->addSql("DROP TABLE xi_filelib_file");
        $this->addSql("DROP TABLE xi_filelib_folder");
        $this->addSql("DROP TABLE xi_filelib_resource");
        $this->addSql("DROP TABLE File");
        $this->addSql("ALTER TABLE article DROP published");
    }
}
