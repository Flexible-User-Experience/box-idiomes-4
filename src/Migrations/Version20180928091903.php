<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180928091903 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice CHANGE is_payed is_payed TINYINT(1) DEFAULT \'0\', CHANGE is_sended is_sended TINYINT(1) DEFAULT \'0\', CHANGE is_sepa_xml_generated is_sepa_xml_generated TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE receipt CHANGE is_payed is_payed TINYINT(1) DEFAULT \'0\', CHANGE is_sended is_sended TINYINT(1) DEFAULT \'0\', CHANGE is_sepa_xml_generated is_sepa_xml_generated TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice CHANGE is_payed is_payed TINYINT(1) DEFAULT NULL, CHANGE is_sended is_sended TINYINT(1) DEFAULT NULL, CHANGE is_sepa_xml_generated is_sepa_xml_generated TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt CHANGE is_payed is_payed TINYINT(1) DEFAULT NULL, CHANGE is_sended is_sended TINYINT(1) DEFAULT NULL, CHANGE is_sepa_xml_generated is_sepa_xml_generated TINYINT(1) DEFAULT NULL');
    }
}
