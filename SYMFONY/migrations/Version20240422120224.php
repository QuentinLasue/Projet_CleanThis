<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422120224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe ADD id_role_id INT NOT NULL');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B989E8BDC ON employe (id_role_id)');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A1B65292');
        $this->addSql('DROP INDEX IDX_57698A6A1B65292 ON role');
        $this->addSql('ALTER TABLE role DROP employe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B989E8BDC');
        $this->addSql('DROP INDEX IDX_F804D3B989E8BDC ON employe');
        $this->addSql('ALTER TABLE employe DROP id_role_id');
        $this->addSql('ALTER TABLE role ADD employe_id INT NOT NULL');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_57698A6A1B65292 ON role (employe_id)');
    }
}
