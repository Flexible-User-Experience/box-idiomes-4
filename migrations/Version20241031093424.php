<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031093424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receipt_group (id INT AUTO_INCREMENT NOT NULL, training_center_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, base_amount DOUBLE PRECISION NOT NULL, month INT NOT NULL, year INT NOT NULL, INDEX IDX_8958F0F537BE9083 (training_center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt_group ADD CONSTRAINT FK_8958F0F537BE9083 FOREIGN KEY (training_center_id) REFERENCES training_center (id)');
        $this->addSql('ALTER TABLE receipt ADD receipt_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645A4424CF9 FOREIGN KEY (receipt_group_id) REFERENCES receipt_group (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5399B645A4424CF9 ON receipt (receipt_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B645A4424CF9');
        $this->addSql('ALTER TABLE receipt_group DROP FOREIGN KEY FK_8958F0F537BE9083');
        $this->addSql('DROP TABLE receipt_group');
        $this->addSql('DROP INDEX IDX_5399B645A4424CF9 ON receipt');
        $this->addSql('ALTER TABLE receipt DROP receipt_group_id');
    }
}
