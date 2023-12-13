<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122110153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rdvs ADD docteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rdvs ADD CONSTRAINT FK_1FC52A01CF22540A FOREIGN KEY (docteur_id) REFERENCES doctors (id)');
        $this->addSql('CREATE INDEX IDX_1FC52A01CF22540A ON rdvs (docteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rdvs DROP FOREIGN KEY FK_1FC52A01CF22540A');
        $this->addSql('DROP INDEX IDX_1FC52A01CF22540A ON rdvs');
        $this->addSql('ALTER TABLE rdvs DROP docteur_id');
    }
}
