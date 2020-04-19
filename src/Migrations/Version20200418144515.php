<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200418144515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB727ACA70');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBAA23F6C8');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBB168B8C0');
        $this->addSql('CREATE TABLE scene (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, prev_id INT DEFAULT NULL, next_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, attributes TEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', description MEDIUMTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, short_description VARCHAR(255) NOT NULL, INDEX IDX_D979EFDA727ACA70 (parent_id), UNIQUE INDEX UNIQ_D979EFDAB168B8C0 (prev_id), UNIQUE INDEX UNIQ_D979EFDAAA23F6C8 (next_id), INDEX IDX_D979EFDA7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDA727ACA70 FOREIGN KEY (parent_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDAB168B8C0 FOREIGN KEY (prev_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDAAA23F6C8 FOREIGN KEY (next_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE location');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scene DROP FOREIGN KEY FK_D979EFDA727ACA70');
        $this->addSql('ALTER TABLE scene DROP FOREIGN KEY FK_D979EFDAB168B8C0');
        $this->addSql('ALTER TABLE scene DROP FOREIGN KEY FK_D979EFDAAA23F6C8');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, prev_id INT DEFAULT NULL, next_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, attributes TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:simple_array)\', description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, short_description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_5E9E89CB727ACA70 (parent_id), INDEX IDX_5E9E89CB7E3C61F9 (owner_id), UNIQUE INDEX UNIQ_5E9E89CBAA23F6C8 (next_id), UNIQUE INDEX UNIQ_5E9E89CBB168B8C0 (prev_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB727ACA70 FOREIGN KEY (parent_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBAA23F6C8 FOREIGN KEY (next_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBB168B8C0 FOREIGN KEY (prev_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE scene');
    }
}
