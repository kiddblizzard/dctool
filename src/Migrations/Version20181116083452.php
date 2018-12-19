<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181116083452 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device ADD rack_row VARCHAR(10) DEFAULT NULL, ADD rps VARCHAR(50) DEFAULT NULL, CHANGE application_avail_mntrng application_avail_mntrng VARCHAR(100) DEFAULT NULL, CHANGE application_avail_mntrng_tier application_avail_mntrng_tier VARCHAR(100) DEFAULT NULL, CHANGE backup_type backup_type VARCHAR(100) DEFAULT NULL, CHANGE business_area business_area VARCHAR(100) DEFAULT NULL, CHANGE c_business_sector c_business_sector VARCHAR(100) DEFAULT NULL, CHANGE c_system_alias_name c_system_alias_name VARCHAR(100) DEFAULT NULL, CHANGE cluster cluster VARCHAR(100) DEFAULT NULL, CHANGE database_type database_type VARCHAR(100) DEFAULT NULL, CHANGE device_avail_mntrng device_avail_mntrng VARCHAR(100) DEFAULT NULL, CHANGE device_avail_mntrng_tier device_avail_mntrng_tier VARCHAR(100) DEFAULT NULL, CHANGE machine_category machine_category VARCHAR(100) DEFAULT NULL, CHANGE machine_type machine_type VARCHAR(100) DEFAULT NULL, CHANGE mount_type mount_type VARCHAR(100) DEFAULT NULL, CHANGE parent_unique_id parent_unique_id VARCHAR(100) DEFAULT NULL, CHANGE platform platform VARCHAR(100) DEFAULT NULL, CHANGE po po VARCHAR(100) DEFAULT NULL, CHANGE status status VARCHAR(20) DEFAULT NULL, CHANGE storage_type storage_type VARCHAR(100) DEFAULT NULL, CHANGE system_type system_type VARCHAR(100) DEFAULT NULL, CHANGE tcr_scap_id tcr_scap_id VARCHAR(100) DEFAULT NULL, CHANGE terminal_server terminal_server VARCHAR(100) DEFAULT NULL, CHANGE unique_id unique_id VARCHAR(100) DEFAULT NULL, CHANGE unit unit VARCHAR(10) DEFAULT NULL, CHANGE vob_id vob_id VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device DROP rack_row, DROP rps, CHANGE unit unit TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE mount_type mount_type TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE backup_type backup_type TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE cluster cluster TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE unique_id unique_id TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE parent_unique_id parent_unique_id TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE system_type system_type TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE machine_category machine_category TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE machine_type machine_type TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE platform platform TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE c_business_sector c_business_sector TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE business_area business_area TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE c_system_alias_name c_system_alias_name TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE vob_id vob_id TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE tcr_scap_id tcr_scap_id TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE storage_type storage_type TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE database_type database_type TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE terminal_server terminal_server TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE device_avail_mntrng device_avail_mntrng TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE device_avail_mntrng_tier device_avail_mntrng_tier TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE application_avail_mntrng application_avail_mntrng TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE application_avail_mntrng_tier application_avail_mntrng_tier TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE po po TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE status status TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
