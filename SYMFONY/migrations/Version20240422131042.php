<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422131042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avoir (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, adresse_id INT NOT NULL, INDEX IDX_659B1A4319EB6921 (client_id), INDEX IDX_659B1A434DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A4319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A434DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A4319EB6921');
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A434DE7DC5C');
        $this->addSql('DROP TABLE avoir');
    }
}
