<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201001170907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User Learning entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_learning (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_4BD6C11A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_learning_word_group (user_learning_id INT NOT NULL, word_group_id INT NOT NULL, INDEX IDX_49FA69BE73DE7E9 (user_learning_id), INDEX IDX_49FA69BEDAEA0896 (word_group_id), PRIMARY KEY(user_learning_id, word_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_learning_language (user_learning_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_1CD7BB6573DE7E9 (user_learning_id), INDEX IDX_1CD7BB6582F1BAF4 (language_id), PRIMARY KEY(user_learning_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_learning ADD CONSTRAINT FK_4BD6C11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_learning_word_group ADD CONSTRAINT FK_49FA69BE73DE7E9 FOREIGN KEY (user_learning_id) REFERENCES user_learning (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_learning_word_group ADD CONSTRAINT FK_49FA69BEDAEA0896 FOREIGN KEY (word_group_id) REFERENCES word_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_learning_language ADD CONSTRAINT FK_1CD7BB6573DE7E9 FOREIGN KEY (user_learning_id) REFERENCES user_learning (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_learning_language ADD CONSTRAINT FK_1CD7BB6582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_progress CHANGE test_count test_count INT NOT NULL, CHANGE pass_count pass_count INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_learning_word_group DROP FOREIGN KEY FK_49FA69BE73DE7E9');
        $this->addSql('ALTER TABLE user_learning_language DROP FOREIGN KEY FK_1CD7BB6573DE7E9');
        $this->addSql('DROP TABLE user_learning');
        $this->addSql('DROP TABLE user_learning_word_group');
        $this->addSql('DROP TABLE user_learning_language');
        $this->addSql('ALTER TABLE user_progress CHANGE test_count test_count INT DEFAULT 0 NOT NULL, CHANGE pass_count pass_count INT DEFAULT 0 NOT NULL');
    }
}
