<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422132213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, facture_id INT NOT NULL, client_id INT NOT NULL, type_id INT NOT NULL, employe_id INT NOT NULL, description VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_previsionnel DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_1981A66D7F2DEE08 (facture_id), INDEX IDX_1981A66D19EB6921 (client_id), INDEX IDX_1981A66DC54C8C93 (type_id), INDEX IDX_1981A66D1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DC54C8C93 FOREIGN KEY (type_id) REFERENCES type_operation (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D7F2DEE08');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D19EB6921');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DC54C8C93');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D1B65292');
        $this->addSql('DROP TABLE operation');
    }
}
