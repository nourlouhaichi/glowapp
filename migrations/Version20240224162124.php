<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224162124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objectif (id INT AUTO_INCREMENT NOT NULL, programme_id INT DEFAULT NULL, objectif_o VARCHAR(255) NOT NULL, description_o VARCHAR(255) NOT NULL, categorie_o VARCHAR(255) NOT NULL, poid_o DOUBLE PRECISION NOT NULL, taille_o DOUBLE PRECISION NOT NULL, INDEX IDX_E2F8685162BB7AEE (programme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, categorie_pro VARCHAR(255) NOT NULL, plan_pro VARCHAR(255) NOT NULL, note_pro VARCHAR(255) NOT NULL, date_pro DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objectif ADD CONSTRAINT FK_E2F8685162BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objectif DROP FOREIGN KEY FK_E2F8685162BB7AEE');
        $this->addSql('DROP TABLE objectif');
        $this->addSql('DROP TABLE programme');
    }
}
