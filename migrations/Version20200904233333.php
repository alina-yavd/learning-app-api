<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200904233333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F17511DAEA0896');
        $this->addSql('CREATE TABLE word_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word_word_list (word_id INT NOT NULL, word_list_id INT NOT NULL, INDEX IDX_BDADA766E357438D (word_id), INDEX IDX_BDADA76637DC6178 (word_list_id), PRIMARY KEY(word_id, word_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE word_word_list ADD CONSTRAINT FK_BDADA766E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE word_word_list ADD CONSTRAINT FK_BDADA76637DC6178 FOREIGN KEY (word_list_id) REFERENCES word_list (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE word_group');
        $this->addSql('DROP INDEX IDX_C3F17511DAEA0896 ON word');
        $this->addSql('ALTER TABLE word DROP word_group_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word_word_list DROP FOREIGN KEY FK_BDADA76637DC6178');
        $this->addSql('CREATE TABLE word_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE word_word_list');
        $this->addSql('DROP TABLE word_list');
        $this->addSql('ALTER TABLE word ADD word_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F17511DAEA0896 FOREIGN KEY (word_group_id) REFERENCES word_group (id)');
        $this->addSql('CREATE INDEX IDX_C3F17511DAEA0896 ON word (word_group_id)');
    }
}
