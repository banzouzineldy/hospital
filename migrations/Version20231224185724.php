<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224185724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acte_medical ADD patient VARCHAR(255) NOT NULL, ADD examen VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rdvs CHANGE dateautomatique dateautomatique DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acte_medical DROP patient, DROP examen');
        $this->addSql('ALTER TABLE rdvs CHANGE dateautomatique dateautomatique DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
