<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426144638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, label_name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label_book (label_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_7DD36FD33B92F39 (label_id), INDEX IDX_7DD36FD16A2B381 (book_id), PRIMARY KEY(label_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE label_book ADD CONSTRAINT FK_7DD36FD33B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_book ADD CONSTRAINT FK_7DD36FD16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label_book DROP FOREIGN KEY FK_7DD36FD33B92F39');
        $this->addSql('ALTER TABLE label_book DROP FOREIGN KEY FK_7DD36FD16A2B381');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE label_book');
    }
}
