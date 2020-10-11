<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201011112011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE teacher DROP removed_at');
        $this->addSql('ALTER TABLE invoice_line DROP removed_at');
        $this->addSql('ALTER TABLE event DROP removed_at');
        $this->addSql('ALTER TABLE spending DROP removed_at');
        $this->addSql('ALTER TABLE service DROP removed_at');
        $this->addSql('ALTER TABLE city DROP removed_at');
        $this->addSql('ALTER TABLE pre_register DROP removed_at');
        $this->addSql('ALTER TABLE student_absence DROP removed_at');
        $this->addSql('ALTER TABLE tariff DROP removed_at');
        $this->addSql('ALTER TABLE province DROP removed_at');
        $this->addSql('ALTER TABLE spending_category DROP removed_at');
        $this->addSql('ALTER TABLE person DROP removed_at');
        $this->addSql('ALTER TABLE receipt DROP removed_at');
        $this->addSql('ALTER TABLE contact_message DROP removed_at');
        $this->addSql('ALTER TABLE student DROP removed_at');
        $this->addSql('ALTER TABLE teacher_absence DROP removed_at');
        $this->addSql('ALTER TABLE invoice DROP removed_at');
        $this->addSql('ALTER TABLE receipt_line DROP removed_at');
        $this->addSql('ALTER TABLE newsletter_contact DROP removed_at');
        $this->addSql('ALTER TABLE bank DROP removed_at');
        $this->addSql('ALTER TABLE provider DROP removed_at');
        $this->addSql('ALTER TABLE class_group DROP removed_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE city ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE class_group ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contact_message ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_line ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_contact ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE pre_register ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE provider ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE province ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt_line ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE spending ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE spending_category ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE student_absence ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tariff ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher ADD removed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher_absence ADD removed_at DATETIME DEFAULT NULL');
    }
}
