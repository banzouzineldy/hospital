<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104091421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE eldy');
        $this->addSql('ALTER TABLE acte_medical ADD libelle VARCHAR(255) NOT NULL, ADD soin VARCHAR(255) NOT NULL, ADD patient VARCHAR(255) NOT NULL, ADD examen VARCHAR(255) NOT NULL');
       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
       
        $this->addSql('ALTER TABLE acte_medical DROP libelle, DROP soin, DROP patient, DROP examen');
       
    }
}
