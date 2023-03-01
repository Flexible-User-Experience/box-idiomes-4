<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215182801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_user ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE admin_user ADD CONSTRAINT FK_AD8A54A941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_AD8A54A941807E1D ON admin_user (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_user DROP FOREIGN KEY FK_AD8A54A941807E1D');
        $this->addSql('DROP INDEX IDX_AD8A54A941807E1D ON admin_user');
        $this->addSql('ALTER TABLE admin_user DROP teacher_id');
    }
}
