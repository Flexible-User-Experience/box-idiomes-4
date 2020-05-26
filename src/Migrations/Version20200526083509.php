<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526083509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pre_register ADD class_group_id INT DEFAULT NULL, ADD season INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE pre_register ADD CONSTRAINT FK_592F72474A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id)');
        $this->addSql('CREATE INDEX IDX_592F72474A9A1217 ON pre_register (class_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pre_register DROP FOREIGN KEY FK_592F72474A9A1217');
        $this->addSql('DROP INDEX IDX_592F72474A9A1217 ON pre_register');
        $this->addSql('ALTER TABLE pre_register DROP class_group_id, DROP season');
    }
}
