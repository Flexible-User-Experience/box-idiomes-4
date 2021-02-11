<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181003090207 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice CHANGE discount_applied discount_applied TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE receipt CHANGE discount_applied discount_applied TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE student ADD is_payment_exempt TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice CHANGE discount_applied discount_applied TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt CHANGE discount_applied discount_applied TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE student DROP is_payment_exempt');
    }
}
