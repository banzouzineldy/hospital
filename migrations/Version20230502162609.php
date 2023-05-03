<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502162609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous ADD doctors_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA86425CC19 FOREIGN KEY (doctors_id) REFERENCES doctors (id)');
        $this->addSql('CREATE INDEX IDX_C09A9BA86425CC19 ON rendezvous (doctors_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA86425CC19');
        $this->addSql('DROP INDEX IDX_C09A9BA86425CC19 ON rendezvous');
        $this->addSql('ALTER TABLE rendezvous DROP doctors_id');
    }
}
