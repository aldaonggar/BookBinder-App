<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428120915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_label (user_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_EC65ABB0A76ED395 (user_id), INDEX IDX_EC65ABB033B92F39 (label_id), PRIMARY KEY(user_id, label_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_label ADD CONSTRAINT FK_EC65ABB0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_label ADD CONSTRAINT FK_EC65ABB033B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY favorite_library');
        $this->addSql('DROP INDEX favorite_library_idx ON user');
        $this->addSql('ALTER TABLE user DROP favorite_library, DROP sex');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_label DROP FOREIGN KEY FK_EC65ABB0A76ED395');
        $this->addSql('ALTER TABLE user_label DROP FOREIGN KEY FK_EC65ABB033B92F39');
        $this->addSql('DROP TABLE user_label');
        $this->addSql('ALTER TABLE user ADD favorite_library INT DEFAULT NULL, ADD sex VARCHAR(45) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT favorite_library FOREIGN KEY (favorite_library) REFERENCES library (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX favorite_library_idx ON user (favorite_library)');
    }
}
