<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229221906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY fk_user_cin');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AABE530DA');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AABE530DA FOREIGN KEY (cin) REFERENCES user (cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AABE530DA');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_user_cin FOREIGN KEY (cin) REFERENCES user (cin) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AABE530DA FOREIGN KEY (cin) REFERENCES user (cin) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
