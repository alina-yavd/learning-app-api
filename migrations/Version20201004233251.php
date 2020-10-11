<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201004233251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move word translations to Word entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE translations (word_id INT NOT NULL, translation_word_id INT NOT NULL, INDEX IDX_C6B7DA87E357438D (word_id), INDEX IDX_C6B7DA87425BBA7F (translation_word_id), PRIMARY KEY(word_id, translation_word_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87425BBA7F FOREIGN KEY (translation_word_id) REFERENCES word (id)');
        $this->addSql('DROP TABLE word_translation');
        $this->addSql('ALTER TABLE user_learning RENAME INDEX uniq_4bd6c11a76ed395 TO UNIQ_2F8840C2A76ED395');
        $this->addSql('ALTER TABLE user_learning_word_group RENAME INDEX idx_49fa69be73de7e9 TO IDX_4A9CFFC7E770C326');
        $this->addSql('ALTER TABLE user_learning_word_group RENAME INDEX idx_49fa69bedaea0896 TO IDX_4A9CFFC7DAEA0896');
        $this->addSql('ALTER TABLE user_learning_language RENAME INDEX idx_1cd7bb6573de7e9 TO IDX_8B4CE80E770C326');
        $this->addSql('ALTER TABLE user_learning_language RENAME INDEX idx_1cd7bb6582f1baf4 TO IDX_8B4CE8082F1BAF4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE word_translation (id INT AUTO_INCREMENT NOT NULL, word_id INT DEFAULT NULL, language_id INT NOT NULL, text VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8CD8709982F1BAF4 (language_id), INDEX IDX_8CD87099E357438D (word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE word_translation ADD CONSTRAINT FK_8CD8709982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE word_translation ADD CONSTRAINT FK_8CD87099E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('DROP TABLE translations');
        $this->addSql('ALTER TABLE user_learning RENAME INDEX uniq_2f8840c2a76ed395 TO UNIQ_4BD6C11A76ED395');
        $this->addSql('ALTER TABLE user_learning_language RENAME INDEX idx_8b4ce80e770c326 TO IDX_1CD7BB6573DE7E9');
        $this->addSql('ALTER TABLE user_learning_language RENAME INDEX idx_8b4ce8082f1baf4 TO IDX_1CD7BB6582F1BAF4');
        $this->addSql('ALTER TABLE user_learning_word_group RENAME INDEX idx_4a9cffc7e770c326 TO IDX_49FA69BE73DE7E9');
        $this->addSql('ALTER TABLE user_learning_word_group RENAME INDEX idx_4a9cffc7daea0896 TO IDX_49FA69BEDAEA0896');
    }
}
