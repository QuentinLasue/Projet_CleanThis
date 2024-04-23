<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423121230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation ADD adresse_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL, ADD type_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DC54C8C93 FOREIGN KEY (type_id) REFERENCES type_operation (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1981A66D4DE7DC5C ON operation (adresse_id)');
        $this->addSql('CREATE INDEX IDX_1981A66D19EB6921 ON operation (client_id)');
        $this->addSql('CREATE INDEX IDX_1981A66DC54C8C93 ON operation (type_id)');
        $this->addSql('CREATE INDEX IDX_1981A66DA76ED395 ON operation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D4DE7DC5C');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D19EB6921');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DC54C8C93');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DA76ED395');
        $this->addSql('DROP INDEX IDX_1981A66D4DE7DC5C ON operation');
        $this->addSql('DROP INDEX IDX_1981A66D19EB6921 ON operation');
        $this->addSql('DROP INDEX IDX_1981A66DC54C8C93 ON operation');
        $this->addSql('DROP INDEX IDX_1981A66DA76ED395 ON operation');
        $this->addSql('ALTER TABLE operation DROP adresse_id, DROP client_id, DROP type_id, DROP user_id');
    }
}
