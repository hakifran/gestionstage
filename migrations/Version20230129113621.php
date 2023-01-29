<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129113621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE nombreStage(idNombreStage INT NOT NULL AUTO_INCREMENT, nombre INT NOT NULL,idEnseignant INT NOT NULL, idPeriode INT NOT NULL, PRIMARY KEY(idNombreStage))");
        $this->addSql("ALTER TABLE nombreStage ADD CONSTRAINT enseignant_nombreStage FOREIGN KEY (idEnseignant) REFERENCES enseignant(idEnseignant)");
        $this->addSql("ALTER TABLE nombreStage ADD CONSTRAINT periode_nombreStage FOREIGN KEY (idPeriode) REFERENCES periode(idPeriode)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE nombreStage DROP CONSTRAINT enseignant_nombreStage");
        $this->addSql("ALTER TABLE nombreStage DROP CONSTRAINT periode_nombreStage");  
        $this->assSql("DROP TABLE nombreStage");
    }
}