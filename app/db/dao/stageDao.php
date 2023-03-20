<?php
require_once "../app/db/basededonnee.php";

class StageDao
{

    public function create($stage)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "INSERT INTO stage (intituleProjet, nomEntreprise, adresse, attribue, valide, idEtudiant, idEnseignant, idPeriode) VALUES (?,?,?,?,?,?,?,?)";
        try {
            $connexion->prepare($sql)->execute([$stage->getIntituleProjet(), $stage->getNomEntreprise(), $stage->getAdresse(), $stage->getAttribue(), $stage->getValide(), $stage->getIdEtudiant(), $stage->getIdEnseignant(), $stage->getIdPeriode()]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return $connexion->lastInsertId();
    }

    function list() {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM periode";
        return $connexion->query($sql);
    }

    public function get($id)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $stmt = $connexion->prepare("SELECT * FROM periode where idPeriode=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
