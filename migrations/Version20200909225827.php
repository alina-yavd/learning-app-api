<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200909225827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add createdAt and updatedAt columns for Word, WordTranslation and WordGroup';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE word_group ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE word_translation ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE word_group DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE word_translation DROP created_at, DROP updated_at');
    }
}
