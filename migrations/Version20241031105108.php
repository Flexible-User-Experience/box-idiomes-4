<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031105108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipt_group ADD bank_creditor_sepa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt_group ADD CONSTRAINT FK_8958F0F572DB8670 FOREIGN KEY (bank_creditor_sepa_id) REFERENCES bank_creditor_sepa (id)');
        $this->addSql('CREATE INDEX IDX_8958F0F572DB8670 ON receipt_group (bank_creditor_sepa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipt_group DROP FOREIGN KEY FK_8958F0F572DB8670');
        $this->addSql('DROP INDEX IDX_8958F0F572DB8670 ON receipt_group');
        $this->addSql('ALTER TABLE receipt_group DROP bank_creditor_sepa_id');
    }
}
