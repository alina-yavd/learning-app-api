<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200930214836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ApiRefreshToken';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE api_refresh_token (id INT AUTO_INCREMENT NOT NULL, token_id INT NOT NULL, refresh_token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_6F22941941DEE7B9 (token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_refresh_token ADD CONSTRAINT FK_6F22941941DEE7B9 FOREIGN KEY (token_id) REFERENCES api_token (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE api_refresh_token');
    }
}
