<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306175558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD user_cin VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495540F35151 FOREIGN KEY (user_cin) REFERENCES user (cin)');
        $this->addSql('CREATE INDEX IDX_42C8495540F35151 ON reservation (user_cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495540F35151');
        $this->addSql('DROP INDEX IDX_42C8495540F35151 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP user_cin');
    }
}
