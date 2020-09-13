<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200905225223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename Word Answer to Word Translation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE word_translation (id INT AUTO_INCREMENT NOT NULL, word_id INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_8CD87099E357438D (word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE word_translation ADD CONSTRAINT FK_8CD87099E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('DROP TABLE word_answer');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE word_answer (id INT AUTO_INCREMENT NOT NULL, word_id INT NOT NULL, text VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_A8049EABE357438D (word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE word_answer ADD CONSTRAINT FK_A8049EABE357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('DROP TABLE word_translation');
    }
}
