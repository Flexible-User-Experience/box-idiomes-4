<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251025113127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_evaluation (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, has_been_notified TINYINT(1) DEFAULT 0, notification_date DATETIME DEFAULT NULL, has_been_accepted TINYINT(1) DEFAULT 0, accepted_date DATETIME DEFAULT NULL, has_been_closed TINYINT(1) DEFAULT 0, closed_date DATETIME DEFAULT NULL, course INT NOT NULL, evalutaion INT DEFAULT 1 NOT NULL, writting VARCHAR(255) DEFAULT NULL, reading VARCHAR(255) DEFAULT NULL, use_of_english VARCHAR(255) DEFAULT NULL, listening VARCHAR(255) DEFAULT NULL, speaking VARCHAR(255) DEFAULT NULL, behaviour VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, global_mark VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_FEFC4894CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_evaluation ADD CONSTRAINT FK_FEFC4894CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_evaluation DROP FOREIGN KEY FK_FEFC4894CB944F1A');
        $this->addSql('DROP TABLE student_evaluation');
    }
}
