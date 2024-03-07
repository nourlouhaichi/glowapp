<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307120145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservationprog (id INT AUTO_INCREMENT NOT NULL, idprog_id INT DEFAULT NULL, nbrplace INT NOT NULL, approuve TINYINT(1) NOT NULL, INDEX IDX_34D47408CC79C2F5 (idprog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservationprog ADD CONSTRAINT FK_34D47408CC79C2F5 FOREIGN KEY (idprog_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CC79C2F5');
        $this->addSql('DROP TABLE reservation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, idprog_id INT DEFAULT NULL, nbrplace INT NOT NULL, approuve TINYINT(1) NOT NULL, INDEX IDX_42C84955CC79C2F5 (idprog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CC79C2F5 FOREIGN KEY (idprog_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE reservationprog DROP FOREIGN KEY FK_34D47408CC79C2F5');
        $this->addSql('DROP TABLE reservationprog');
    }
}
