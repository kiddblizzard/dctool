<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180820035844 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device ADD po TINYTEXT DEFAULT NULL, CHANGE serial_number serial_number TINYTEXT DEFAULT NULL, CHANGE barcode_number barcode_number TINYTEXT DEFAULT NULL, CHANGE cpu cpu TINYTEXT DEFAULT NULL, CHANGE memory memory TINYTEXT DEFAULT NULL, CHANGE rems_id rems_id TINYTEXT DEFAULT NULL, CHANGE name name TINYTEXT DEFAULT NULL, CHANGE mount_type mount_type TINYTEXT DEFAULT NULL, CHANGE backup_type backup_type TINYTEXT DEFAULT NULL, CHANGE cluster cluster TINYTEXT DEFAULT NULL, CHANGE unique_id unique_id TINYTEXT DEFAULT NULL, CHANGE parent_unique_id parent_unique_id TINYTEXT DEFAULT NULL, CHANGE system_type system_type TINYTEXT DEFAULT NULL, CHANGE machine_category machine_category TINYTEXT DEFAULT NULL, CHANGE machine_type machine_type TINYTEXT DEFAULT NULL, CHANGE platform platform TINYTEXT DEFAULT NULL, CHANGE c_business_sector c_business_sector TINYTEXT DEFAULT NULL, CHANGE business_area business_area TINYTEXT DEFAULT NULL, CHANGE c_system_alias_name c_system_alias_name TINYTEXT DEFAULT NULL, CHANGE vob_id vob_id TINYTEXT DEFAULT NULL, CHANGE tcr_scap_id tcr_scap_id TINYTEXT DEFAULT NULL, CHANGE storage_type storage_type TINYTEXT DEFAULT NULL, CHANGE database_type database_type TINYTEXT DEFAULT NULL, CHANGE commodity_device commodity_device TINYINT(1) DEFAULT NULL, CHANGE terminal_server terminal_server TINYTEXT DEFAULT NULL, CHANGE device_avail_mntrng device_avail_mntrng TINYTEXT DEFAULT NULL, CHANGE device_avail_mntrng_tier device_avail_mntrng_tier TINYTEXT DEFAULT NULL, CHANGE application_avail_mntrng application_avail_mntrng TINYTEXT DEFAULT NULL, CHANGE application_avail_mntrng_tier application_avail_mntrng_tier TINYTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device DROP po, CHANGE serial_number serial_number TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE barcode_number barcode_number TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE cpu cpu TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE memory memory TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE rems_id rems_id TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE name name TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE mount_type mount_type TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE backup_type backup_type TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE cluster cluster TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE unique_id unique_id TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE parent_unique_id parent_unique_id TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE system_type system_type TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE machine_category machine_category TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE machine_type machine_type TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE platform platform TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE c_business_sector c_business_sector TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE business_area business_area TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE c_system_alias_name c_system_alias_name TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE vob_id vob_id TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE tcr_scap_id tcr_scap_id TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE storage_type storage_type TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE database_type database_type TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE commodity_device commodity_device TINYINT(1) NOT NULL, CHANGE terminal_server terminal_server TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE device_avail_mntrng device_avail_mntrng TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE device_avail_mntrng_tier device_avail_mntrng_tier TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE application_avail_mntrng application_avail_mntrng TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE application_avail_mntrng_tier application_avail_mntrng_tier TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
