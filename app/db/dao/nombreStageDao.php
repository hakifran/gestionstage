<?php
require_once "../app/db/basededonnee.php";

class NombreStageDao
{

    public function create($stage)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "INSERT INTO nombreStage (nombre, idEnseignant, idPeriode) VALUES (?,?,?)";
        try {
            $connexion->prepare($sql)->execute([$stage->getNombre(), $stage->getIdEnseignant(), $stage->getIdPeriode()]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return $connexion->lastInsertId();
    }

    function list($idUtilisateur) {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM nombreStage JOIN periode ON(nombreStage.idPeriode=periode.idPeriode)
        JOIN enseignant ON(nombreStage.idEnseignant=enseignant.idEnseignant)
        JOIN personne ON(enseignant.idPersonne=personne.idPersonne)
        where enseignant.idEnseignant=" . $idUtilisateur . "";

        return $connexion->query($sql);
    }

    public function get($id)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM nombreStage JOIN periode ON(nombreStage.idPeriode=periode.idPeriode)
        JOIN enseignant ON(nombreStage.idEnseignant=enseignant.idEnseignant)
        JOIN personne ON(enseignant.idPersonne=personne.idPersonne)
        where nombreStage.idNombreStage=:id";
        $stmt = $connexion->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function nombreStageParEnseignantParPeriode($idPeriode, $idEnseignant)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM nombreStage JOIN periode ON(nombreStage.idPeriode=periode.idPeriode)
        JOIN enseignant ON(nombreStage.idEnseignant=enseignant.idEnseignant)
        JOIN personne ON(enseignant.idPersonne=personne.idPersonne)
        where enseignant.idEnseignant=" . $idEnseignant . " AND periode.idPeriode=" . $idPeriode . "";

        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();

    }
}
