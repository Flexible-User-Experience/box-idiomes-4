<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123190814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person ADD bank_creditor_sepa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17672DB8670 FOREIGN KEY (bank_creditor_sepa_id) REFERENCES bank_creditor_sepa (id)');
        $this->addSql('CREATE INDEX IDX_34DCD17672DB8670 ON person (bank_creditor_sepa_id)');
        $this->addSql('ALTER TABLE student ADD bank_creditor_sepa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3372DB8670 FOREIGN KEY (bank_creditor_sepa_id) REFERENCES bank_creditor_sepa (id)');
        $this->addSql('CREATE INDEX IDX_B723AF3372DB8670 ON student (bank_creditor_sepa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17672DB8670');
        $this->addSql('DROP INDEX IDX_34DCD17672DB8670 ON person');
        $this->addSql('ALTER TABLE person DROP bank_creditor_sepa_id');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3372DB8670');
        $this->addSql('DROP INDEX IDX_B723AF3372DB8670 ON student');
        $this->addSql('ALTER TABLE student DROP bank_creditor_sepa_id');
    }
}
