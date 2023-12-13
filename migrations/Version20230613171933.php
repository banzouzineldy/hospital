<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613171933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, unite_id INT DEFAULT NULL, num_chambre VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_C509E4FFED5CA9E6 (service_id), INDEX IDX_C509E4FFEC4A74AB (unite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hospitalisation (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, pathologie_id INT DEFAULT NULL, chambre_id INT DEFAULT NULL, lit_id INT DEFAULT NULL, service_id INT DEFAULT NULL, specialite_id INT DEFAULT NULL, type_admission VARCHAR(255) NOT NULL, motif_admission VARCHAR(255) NOT NULL, date_entree DATETIME NOT NULL, date_sortie DATETIME DEFAULT NULL, motif_sortie VARCHAR(255) DEFAULT NULL, INDEX IDX_67C059596B899279 (patient_id), INDEX IDX_67C05959E7F789D4 (pathologie_id), INDEX IDX_67C059599B177F54 (chambre_id), INDEX IDX_67C05959278B5057 (lit_id), INDEX IDX_67C05959ED5CA9E6 (service_id), INDEX IDX_67C059592195E0F0 (specialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lit (id INT AUTO_INCREMENT NOT NULL, chambre_id INT DEFAULT NULL, num_lit VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_5DDB8E9D9B177F54 (chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pathologie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qualification (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FFED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FFEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C059596B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C05959E7F789D4 FOREIGN KEY (pathologie_id) REFERENCES pathologie (id)');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C059599B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C05959278B5057 FOREIGN KEY (lit_id) REFERENCES lit (id)');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C05959ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE hospitalisation ADD CONSTRAINT FK_67C059592195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE lit ADD CONSTRAINT FK_5DDB8E9D9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE rdvs CHANGE motif motif LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD qualification_id INT DEFAULT NULL, ADD unite_id INT DEFAULT NULL, ADD service_id INT DEFAULT NULL, ADD fonction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491A75EE38 FOREIGN KEY (qualification_id) REFERENCES qualification (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64957889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491A75EE38 ON user (qualification_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649EC4A74AB ON user (unite_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ED5CA9E6 ON user (service_id)');
        $this->addSql('CREATE INDEX IDX_8D93D64957889920 ON user (fonction_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64957889920');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491A75EE38');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ED5CA9E6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EC4A74AB');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FFED5CA9E6');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FFEC4A74AB');
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C059596B899279');
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C05959E7F789D4');
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C059599B177F54');
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C05959278B5057');
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C05959ED5CA9E6');
        $this->addSql('ALTER TABLE hospitalisation DROP FOREIGN KEY FK_67C059592195E0F0');
        $this->addSql('ALTER TABLE lit DROP FOREIGN KEY FK_5DDB8E9D9B177F54');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE hospitalisation');
        $this->addSql('DROP TABLE lit');
        $this->addSql('DROP TABLE pathologie');
        $this->addSql('DROP TABLE qualification');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE unite');
        $this->addSql('ALTER TABLE rdvs CHANGE motif motif VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_8D93D6491A75EE38 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649EC4A74AB ON user');
        $this->addSql('DROP INDEX IDX_8D93D649ED5CA9E6 ON user');
        $this->addSql('DROP INDEX IDX_8D93D64957889920 ON user');
        $this->addSql('ALTER TABLE user DROP qualification_id, DROP unite_id, DROP service_id, DROP fonction_id');
    }
}
