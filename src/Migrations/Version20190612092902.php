<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612092902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E727ACA70 FOREIGN KEY (parent_id) REFERENCES device (id)');
        $this->addSql('CREATE INDEX IDX_92FB68E727ACA70 ON device (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E727ACA70');
        $this->addSql('DROP INDEX IDX_92FB68E727ACA70 ON device');
        $this->addSql('ALTER TABLE device DROP parent_id');
    }
}
