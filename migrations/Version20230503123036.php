<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503123036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, doctors VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, patient VARCHAR(255) NOT NULL, date_fin DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_C09A9BA82195E0F0');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_C09A9BA86425CC19');
        $this->addSql('DROP INDEX IDX_C09A9BA82195E0F0 ON rdv');
        $this->addSql('DROP INDEX IDX_C09A9BA86425CC19 ON rdv');
        $this->addSql('ALTER TABLE rdv DROP specialite_id, DROP doctors_id, DROP patient, DROP date_rendezvous, DROP heure_rendezvous');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('ALTER TABLE rdv ADD specialite_id INT DEFAULT NULL, ADD doctors_id INT DEFAULT NULL, ADD patient VARCHAR(255) NOT NULL, ADD date_rendezvous VARCHAR(255) NOT NULL, ADD heure_rendezvous DATE NOT NULL');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_C09A9BA82195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_C09A9BA86425CC19 FOREIGN KEY (doctors_id) REFERENCES doctors (id)');
        $this->addSql('CREATE INDEX IDX_C09A9BA82195E0F0 ON rdv (specialite_id)');
        $this->addSql('CREATE INDEX IDX_C09A9BA86425CC19 ON rdv (doctors_id)');
    }
}
