<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129102604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE stage(idStage INT NOT NULL AUTO_INCREMENT, intituleProjet VARCHAR(255) NOT NULL, nomEntreprise VARCHAR(255) NOT NULL, adresse INT NOT NULL,attribue BOOLEAN DEFAULT(0), valide BOOLEAN DEFAULT(0),idEtudiant INT, idEnseignant INT, PRIMARY KEY(idStage))");
        $this->addSql("ALTER TABLE stage ADD CONSTRAINT etudiant_stage FOREIGN KEY (idEtudiant) REFERENCES etudiant(idEtudiant)");
        $this->addSql("ALTER TABLE stage ADD CONSTRAINT enseignant_stage FOREIGN KEY (idEnseignant) REFERENCES enseignant(idEnseignant)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE stage DROP CONSTRAINT etudiant_stage");
        $this->addSql("ALTER TABLE stage DROP CONSTRAINT enseignant_stage");  
        $this->assSql("DROP TABLE stage");
    }
}