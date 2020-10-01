<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200911235227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add language and translation columns for word groups';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word_group ADD language_id INT NOT NULL, ADD translation_id INT NOT NULL');
        $this->addSql('UPDATE word_group SET language_id=1, translation_id=2');
        $this->addSql('ALTER TABLE word_group ADD CONSTRAINT FK_B3734F0382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE word_group ADD CONSTRAINT FK_B3734F039CAA2B25 FOREIGN KEY (translation_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_B3734F0382F1BAF4 ON word_group (language_id)');
        $this->addSql('CREATE INDEX IDX_B3734F039CAA2B25 ON word_group (translation_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE word_group DROP FOREIGN KEY FK_B3734F0382F1BAF4');
        $this->addSql('ALTER TABLE word_group DROP FOREIGN KEY FK_B3734F039CAA2B25');
        $this->addSql('DROP INDEX IDX_B3734F0382F1BAF4 ON word_group');
        $this->addSql('DROP INDEX IDX_B3734F039CAA2B25 ON word_group');
        $this->addSql('ALTER TABLE word_group DROP language_id, DROP translation_id');
    }
}
