<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180809062608 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE port (id INT AUTO_INCREMENT NOT NULL, device_id TINYTEXT NOT NULL, ip TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, manufacturer_id INT DEFAULT NULL, model TINYTEXT NOT NULL, type TINYTEXT NOT NULL, INDEX IDX_D79572D9A23B42D (manufacturer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rack (id INT AUTO_INCREMENT NOT NULL, name TINYTEXT NOT NULL, longitude TINYTEXT NOT NULL, latitude TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (id INT AUTO_INCREMENT NOT NULL, name TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, site_id INT DEFAULT NULL, rack_id INT DEFAULT NULL, serial_number TINYTEXT NOT NULL, barcode_number TINYTEXT NOT NULL, cpu TINYTEXT NOT NULL, memory TINYTEXT NOT NULL, rems_id TINYTEXT NOT NULL, name TINYTEXT NOT NULL, mount_type TINYTEXT NOT NULL, backup_type TINYTEXT NOT NULL, cluster TINYTEXT NOT NULL, unique_id TINYTEXT NOT NULL, parent_unique_id TINYTEXT NOT NULL, system_type TINYTEXT NOT NULL, machine_category TINYTEXT NOT NULL, machine_type TINYTEXT NOT NULL, platform TINYTEXT NOT NULL, c_business_sector TINYTEXT NOT NULL, business_area TINYTEXT NOT NULL, c_system_alias_name TINYTEXT NOT NULL, vob_id TINYTEXT NOT NULL, tcr_scap_id TINYTEXT NOT NULL, storage_type TINYTEXT NOT NULL, database_type TINYTEXT NOT NULL, commodity_device TINYINT(1) NOT NULL, terminal_server TINYTEXT NOT NULL, device_avail_mntrng TINYTEXT NOT NULL, device_avail_mntrng_tier TINYTEXT NOT NULL, application_avail_mntrng TINYTEXT NOT NULL, application_avail_mntrng_tier TINYTEXT NOT NULL, INDEX IDX_92FB68E7975B7E7 (model_id), INDEX IDX_92FB68EF6BD1646 (site_id), INDEX IDX_92FB68E8E86A33E (rack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, country TINYTEXT NOT NULL, city TINYTEXT NOT NULL, address TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9A23B42D FOREIGN KEY (manufacturer_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E8E86A33E FOREIGN KEY (rack_id) REFERENCES rack (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E7975B7E7');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E8E86A33E');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9A23B42D');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EF6BD1646');
        $this->addSql('DROP TABLE port');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE rack');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE site');
    }
}
