<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129085317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE etudiant(idEtudiant INT NOT NULL AUTO_INCREMENT, numeroEtudiant VARCHAR(255) NOT NULL, numeroNational VARCHAR(255) NOT NULL, parcours VARCHAR(255) NOT NULL, idPersonne INT NOT NULL, PRIMARY KEY(idEtudiant))");
        $this->addSql("ALTER TABLE etudiant ADD CONSTRAINT personne_etudiant FOREIGN KEY (idPersonne) REFERENCES personne(idPersonne) ON DELETE CASCADE");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE etudiant DROP CONSTRAINT personne_etudiant");  
        $this->assSql("DROP TABLE etudiant");
    }
}