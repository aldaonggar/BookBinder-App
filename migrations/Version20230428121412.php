<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428121412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD favorite_library_id INT DEFAULT NULL, ADD sex VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E0EEA32A FOREIGN KEY (favorite_library_id) REFERENCES library (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E0EEA32A ON user (favorite_library_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E0EEA32A');
        $this->addSql('DROP INDEX IDX_8D93D649E0EEA32A ON user');
        $this->addSql('ALTER TABLE user DROP favorite_library_id, DROP sex');
    }
}
