<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213062351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hospitalisation ADD agent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C059593414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67C059593414710B ON hospitalisation (agent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C059593414710B');
        $this->addSql('DROP INDEX IDX_67C059593414710B ON hospitalisation');
        $this->addSql('ALTER TABLE hospitalisation DROP agent_id');
    }
}
