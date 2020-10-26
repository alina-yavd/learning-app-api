<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201001130110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User Progress entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_progress (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, word_id INT NOT NULL, test_count INT NOT NULL DEFAULT 0, pass_count INT NOT NULL DEFAULT 0, INDEX IDX_C28C1646A76ED395 (user_id), INDEX IDX_C28C1646E357438D (word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_progress ADD CONSTRAINT FK_C28C1646A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_progress ADD CONSTRAINT FK_C28C1646E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_progress');
    }
}
