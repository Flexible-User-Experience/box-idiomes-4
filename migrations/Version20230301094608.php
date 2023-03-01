<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301094608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mailing_students_notification_message (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, class_group_id INT DEFAULT NULL, training_center_id INT DEFAULT NULL, message TEXT NOT NULL, send_date DATETIME DEFAULT NULL, is_sended TINYINT(1) DEFAULT 0 NOT NULL, filter_start_date DATE DEFAULT NULL, filter_end_date DATE DEFAULT NULL, filtered_classroom INT DEFAULT NULL, total_target_students INT DEFAULT 0 NOT NULL, total_delivered_errors INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_BC4E7C6641807E1D (teacher_id), INDEX IDX_BC4E7C664A9A1217 (class_group_id), INDEX IDX_BC4E7C6637BE9083 (training_center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mailing_students_notification_message ADD CONSTRAINT FK_BC4E7C6641807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE mailing_students_notification_message ADD CONSTRAINT FK_BC4E7C664A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE mailing_students_notification_message ADD CONSTRAINT FK_BC4E7C6637BE9083 FOREIGN KEY (training_center_id) REFERENCES training_center (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mailing_students_notification_message DROP FOREIGN KEY FK_BC4E7C6641807E1D');
        $this->addSql('ALTER TABLE mailing_students_notification_message DROP FOREIGN KEY FK_BC4E7C664A9A1217');
        $this->addSql('ALTER TABLE mailing_students_notification_message DROP FOREIGN KEY FK_BC4E7C6637BE9083');
        $this->addSql('DROP TABLE mailing_students_notification_message');
    }
}
