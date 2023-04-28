<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426143710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, score SMALLINT DEFAULT NULL, INDEX IDX_D889262216A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262216A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE library CHANGE adress streetname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY favorite_library');
        $this->addSql('DROP INDEX favorite_library_idx ON user');
        $this->addSql('ALTER TABLE user DROP favorite_library');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262216A2B381');
        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE user ADD favorite_library INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT favorite_library FOREIGN KEY (favorite_library) REFERENCES library (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX favorite_library_idx ON user (favorite_library)');
        $this->addSql('ALTER TABLE library CHANGE streetname adress VARCHAR(255) NOT NULL');
    }
}
