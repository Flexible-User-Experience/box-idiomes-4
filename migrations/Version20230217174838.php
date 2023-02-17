<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217174838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training_center (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_731C2C4B5E237E06 (name), INDEX IDX_731C2C4B8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_center ADD CONSTRAINT FK_731C2C4B8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE class_group ADD training_center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE class_group ADD CONSTRAINT FK_8B1765F337BE9083 FOREIGN KEY (training_center_id) REFERENCES training_center (id)');
        $this->addSql('CREATE INDEX IDX_8B1765F337BE9083 ON class_group (training_center_id)');
        $this->addSql('ALTER TABLE invoice ADD training_center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174437BE9083 FOREIGN KEY (training_center_id) REFERENCES training_center (id)');
        $this->addSql('CREATE INDEX IDX_9065174437BE9083 ON invoice (training_center_id)');
        $this->addSql('ALTER TABLE receipt ADD training_center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B64537BE9083 FOREIGN KEY (training_center_id) REFERENCES training_center (id)');
        $this->addSql('CREATE INDEX IDX_5399B64537BE9083 ON receipt (training_center_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE class_group DROP FOREIGN KEY FK_8B1765F337BE9083');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174437BE9083');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B64537BE9083');
        $this->addSql('ALTER TABLE training_center DROP FOREIGN KEY FK_731C2C4B8BAC62AF');
        $this->addSql('DROP TABLE training_center');
        $this->addSql('DROP INDEX IDX_8B1765F337BE9083 ON class_group');
        $this->addSql('ALTER TABLE class_group DROP training_center_id');
        $this->addSql('DROP INDEX IDX_9065174437BE9083 ON invoice');
        $this->addSql('ALTER TABLE invoice DROP training_center_id');
        $this->addSql('DROP INDEX IDX_5399B64537BE9083 ON receipt');
        $this->addSql('ALTER TABLE receipt DROP training_center_id');
    }
}
