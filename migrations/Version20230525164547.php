<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525164547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient ADD date_enregistrement DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE rdvs CHANGE date_enregistrement date_enregistrement DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP date_enregistrement');
        $this->addSql('ALTER TABLE rdvs CHANGE date_enregistrement date_enregistrement DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
