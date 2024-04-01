<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307145721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorypro (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_prog (id INT AUTO_INCREMENT NOT NULL, prog_comment_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A8BE2C15F6218C8 (prog_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objectif (id INT AUTO_INCREMENT NOT NULL, programme_id INT DEFAULT NULL, objectif_o VARCHAR(255) NOT NULL, description_o VARCHAR(255) NOT NULL, categorie_o VARCHAR(255) NOT NULL, poid_o DOUBLE PRECISION NOT NULL, taille_o DOUBLE PRECISION NOT NULL, INDEX IDX_E2F8685162BB7AEE (programme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, categorypro_id INT DEFAULT NULL, plan_pro VARCHAR(255) NOT NULL, note_pro VARCHAR(255) NOT NULL, date_pro DATE NOT NULL, image VARCHAR(255) DEFAULT NULL, titre_pro VARCHAR(255) NOT NULL, place_dispo INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_3DDCB9FFA1BC476F (categorypro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, programe_id INT DEFAULT NULL, rating_value NUMERIC(10, 1) NOT NULL, INDEX IDX_D889262272E8E47F (programe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationprog (id INT AUTO_INCREMENT NOT NULL, idprog_id INT DEFAULT NULL, nbrplace INT NOT NULL, approuve TINYINT(1) NOT NULL, INDEX IDX_34D47408CC79C2F5 (idprog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_prog ADD CONSTRAINT FK_A8BE2C15F6218C8 FOREIGN KEY (prog_comment_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE objectif ADD CONSTRAINT FK_E2F8685162BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFA1BC476F FOREIGN KEY (categorypro_id) REFERENCES categorypro (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262272E8E47F FOREIGN KEY (programe_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE reservationprog ADD CONSTRAINT FK_34D47408CC79C2F5 FOREIGN KEY (idprog_id) REFERENCES programme (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_prog DROP FOREIGN KEY FK_A8BE2C15F6218C8');
        $this->addSql('ALTER TABLE objectif DROP FOREIGN KEY FK_E2F8685162BB7AEE');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFA1BC476F');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262272E8E47F');
        $this->addSql('ALTER TABLE reservationprog DROP FOREIGN KEY FK_34D47408CC79C2F5');
        $this->addSql('DROP TABLE categorypro');
        $this->addSql('DROP TABLE comment_prog');
        $this->addSql('DROP TABLE objectif');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE reservationprog');
    }
}
