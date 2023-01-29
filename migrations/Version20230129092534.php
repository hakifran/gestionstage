<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129092534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE enseignant(idEnseignant INT NOT NULL AUTO_INCREMENT, titre VARCHAR(255) NOT NULL, specialisation VARCHAR(255) NOT NULL, idPersonne INT NOT NULL, PRIMARY KEY(idEnseignant))");
        $this->addSql("ALTER TABLE enseignant ADD CONSTRAINT personne_enseignant FOREIGN KEY (idPersonne) REFERENCES personne(idPersonne) ON DELETE CASCADE");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE enseignant DROP CONSTRAINT personne_enseignant");  
        $this->assSql("DROP TABLE enseignant");
    }
}