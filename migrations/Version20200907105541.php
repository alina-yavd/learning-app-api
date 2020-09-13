<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200907105541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create language table, add English and Russian language, associate word and word_translation with language.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(2) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO `language` (`id`, `code`, `name`) VALUES (NULL, "en", "English"), (NULL, "ru", "Russian (Русский)")');
        $this->addSql('ALTER TABLE word ADD language_id INT NOT NULL');
        $this->addSql('UPDATE word SET language_id=1');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F1751182F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_C3F1751182F1BAF4 ON word (language_id)');
        $this->addSql('ALTER TABLE word_translation ADD language_id INT NOT NULL, CHANGE word_id word_id INT DEFAULT NULL');
        $this->addSql('UPDATE word_translation SET language_id=2');
        $this->addSql('ALTER TABLE word_translation ADD CONSTRAINT FK_8CD8709982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_8CD8709982F1BAF4 ON word_translation (language_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F1751182F1BAF4');
        $this->addSql('ALTER TABLE word_translation DROP FOREIGN KEY FK_8CD8709982F1BAF4');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP INDEX IDX_C3F1751182F1BAF4 ON word');
        $this->addSql('ALTER TABLE word DROP language_id');
        $this->addSql('DROP INDEX IDX_8CD8709982F1BAF4 ON word_translation');
        $this->addSql('ALTER TABLE word_translation DROP language_id, CHANGE word_id word_id INT NOT NULL');
    }
}
