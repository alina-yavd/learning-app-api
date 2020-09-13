<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200904232514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create word, word_answer, word_group tables and make associations.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE word_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word (id INT AUTO_INCREMENT NOT NULL, word_group_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_C3F17511DAEA0896 (word_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word_answer (id INT AUTO_INCREMENT NOT NULL, word_id INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_A8049EABE357438D (word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F17511DAEA0896 FOREIGN KEY (word_group_id) REFERENCES word_group (id)');
        $this->addSql('ALTER TABLE word_answer ADD CONSTRAINT FK_A8049EABE357438D FOREIGN KEY (word_id) REFERENCES word (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word_answer DROP FOREIGN KEY FK_A8049EABE357438D');
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F17511DAEA0896');
        $this->addSql('DROP TABLE word');
        $this->addSql('DROP TABLE word_answer');
        $this->addSql('DROP TABLE word_group');
    }
}
