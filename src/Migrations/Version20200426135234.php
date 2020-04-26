<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426135234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scene DROP INDEX UNIQ_D979EFDAAA23F6C8, ADD INDEX IDX_D979EFDAAA23F6C8 (next_id)');
        $this->addSql('ALTER TABLE scene DROP INDEX UNIQ_D979EFDAB168B8C0, ADD INDEX IDX_D979EFDAB168B8C0 (prev_id)');
        $this->addSql('ALTER TABLE scene CHANGE type type VARCHAR(255) NOT NULL, CHANGE short_description short_description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scene DROP INDEX IDX_D979EFDAB168B8C0, ADD UNIQUE INDEX UNIQ_D979EFDAB168B8C0 (prev_id)');
        $this->addSql('ALTER TABLE scene DROP INDEX IDX_D979EFDAAA23F6C8, ADD UNIQUE INDEX UNIQ_D979EFDAAA23F6C8 (next_id)');
        $this->addSql('ALTER TABLE scene CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE short_description short_description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
