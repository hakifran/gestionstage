<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129112126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE preference(idPreference INT NOT NULL AUTO_INCREMENT, idStage INT NOT NULL, idEnseignant INT NOT NULL, date_ajout DATE NOT NULL, PRIMARY KEY(idPreference))");
        $this->addSql("ALTER TABLE preference ADD CONSTRAINT enseignant_preference FOREIGN KEY (idEnseignant) REFERENCES enseignant(idEnseignant)");
        $this->addSql("ALTER TABLE preference ADD CONSTRAINT stage_preference FOREIGN KEY (idStage) REFERENCES stage(idStage)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE preference DROP CONSTRAINT enseignant_preference");
        $this->addSql("ALTER TABLE preference DROP CONSTRAINT stage_preference");  
        $this->assSql("DROP TABLE preference");
    }
}