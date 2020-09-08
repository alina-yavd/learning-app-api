<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200905223513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cleanup, rename word_lists to word_group.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word_word_list DROP FOREIGN KEY FK_BDADA76637DC6178');
        $this->addSql('CREATE TABLE word_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word_word_group (word_id INT NOT NULL, word_group_id INT NOT NULL, INDEX IDX_DA847DA1E357438D (word_id), INDEX IDX_DA847DA1DAEA0896 (word_group_id), PRIMARY KEY(word_id, word_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE word_word_group ADD CONSTRAINT FK_DA847DA1E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE word_word_group ADD CONSTRAINT FK_DA847DA1DAEA0896 FOREIGN KEY (word_group_id) REFERENCES word_group (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE word_list');
        $this->addSql('DROP TABLE word_word_list');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word_word_group DROP FOREIGN KEY FK_DA847DA1DAEA0896');
        $this->addSql('CREATE TABLE word_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE word_word_list (word_id INT NOT NULL, word_list_id INT NOT NULL, INDEX IDX_BDADA766E357438D (word_id), INDEX IDX_BDADA76637DC6178 (word_list_id), PRIMARY KEY(word_id, word_list_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE word_word_list ADD CONSTRAINT FK_BDADA76637DC6178 FOREIGN KEY (word_list_id) REFERENCES word_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE word_word_list ADD CONSTRAINT FK_BDADA766E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE word_word_group');
        $this->addSql('DROP TABLE word_group');
    }
}
