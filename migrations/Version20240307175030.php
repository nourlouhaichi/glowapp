<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307175030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_prog ADD user_cin VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_prog ADD CONSTRAINT FK_A8BE2C140F35151 FOREIGN KEY (user_cin) REFERENCES user (cin)');
        $this->addSql('CREATE INDEX IDX_A8BE2C140F35151 ON comment_prog (user_cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_prog DROP FOREIGN KEY FK_A8BE2C140F35151');
        $this->addSql('DROP INDEX IDX_A8BE2C140F35151 ON comment_prog');
        $this->addSql('ALTER TABLE comment_prog DROP user_cin');
    }
}
