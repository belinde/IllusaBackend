<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407154602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, prev_id INT DEFAULT NULL, next_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, attributes JSON NOT NULL, size INT NOT NULL, contains INT NOT NULL, INDEX IDX_5E9E89CB727ACA70 (parent_id), UNIQUE INDEX UNIQ_5E9E89CBB168B8C0 (prev_id), UNIQUE INDEX UNIQ_5E9E89CBAA23F6C8 (next_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB727ACA70 FOREIGN KEY (parent_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBB168B8C0 FOREIGN KEY (prev_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBAA23F6C8 FOREIGN KEY (next_id) REFERENCES location (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB727ACA70');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBB168B8C0');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBAA23F6C8');
        $this->addSql('DROP TABLE location');
    }
}
