<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213183021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON user');
        $this->addSql('ALTER TABLE user ADD cin VARCHAR(8) NOT NULL, ADD lastname VARCHAR(100) NOT NULL, ADD firstname VARCHAR(100) NOT NULL, ADD gender VARCHAR(25) NOT NULL, ADD datebirth DATE NOT NULL, ADD phone VARCHAR(8) NOT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP id');
        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD id INT AUTO_INCREMENT NOT NULL, DROP cin, DROP lastname, DROP firstname, DROP gender, DROP datebirth, DROP phone, DROP created_at, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
