<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405004925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE installation (id INT AUTO_INCREMENT NOT NULL, project_name VARCHAR(255) DEFAULT NULL, planned_date DATE NOT NULL, hostname LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', location LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', trellis_ids LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', change_id VARCHAR(255) DEFAULT NULL, sltn VARCHAR(255) DEFAULT NULL, po VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receiving ADD detail LONGTEXT DEFAULT NULL, ADD delivery_info LONGTEXT DEFAULT NULL, ADD access TINYINT(1) NOT NULL, ADD status VARCHAR(20) DEFAULT \'new\' NOT NULL, ADD type VARCHAR(20) DEFAULT NULL, DROP project_name, DROP hostname, DROP location, DROP trellis_ids, DROP change_id, DROP sltn, DROP po');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE installation');
        $this->addSql('ALTER TABLE receiving ADD project_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD hostname LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', ADD location LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', ADD trellis_ids LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', ADD change_id VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD sltn VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD po VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP detail, DROP delivery_info, DROP access, DROP status, DROP type');
    }
}
