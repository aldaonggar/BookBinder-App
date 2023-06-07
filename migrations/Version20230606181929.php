<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606181929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_book (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_25E3BA0EA76ED395 (user_id), INDEX IDX_25E3BA0E16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_book ADD CONSTRAINT FK_25E3BA0EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_book ADD CONSTRAINT FK_25E3BA0E16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262216A2B381');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF816A2B381');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF8A76ED395');
        $this->addSql('ALTER TABLE label_book DROP FOREIGN KEY FK_7DD36FD16A2B381');
        $this->addSql('ALTER TABLE label_book DROP FOREIGN KEY FK_7DD36FD33B92F39');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('DROP TABLE label_book');
        $this->addSql('DROP TABLE label');
        // $this->addSql('ALTER TABLE book DROP synopsis, CHANGE cover cover VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP password_not_hashed, CHANGE password password VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, score SMALLINT DEFAULT NULL, INDEX IDX_D889262216A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_B164EFF8A76ED395 (user_id), INDEX IDX_B164EFF816A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE label_book (label_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_7DD36FD33B92F39 (label_id), INDEX IDX_7DD36FD16A2B381 (book_id), PRIMARY KEY(label_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, label_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262216A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_book ADD CONSTRAINT FK_7DD36FD16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_book ADD CONSTRAINT FK_7DD36FD33B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_book DROP FOREIGN KEY FK_25E3BA0EA76ED395');
        $this->addSql('ALTER TABLE favorite_book DROP FOREIGN KEY FK_25E3BA0E16A2B381');
        $this->addSql('DROP TABLE favorite_book');
        $this->addSql('ALTER TABLE user ADD password_not_hashed VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD synopsis VARCHAR(10000) DEFAULT NULL, CHANGE cover cover VARCHAR(255) DEFAULT NULL');
    }
}
