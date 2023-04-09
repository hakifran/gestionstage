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

    function list($idUtilisateur, $typeUtilisateur) {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN " . $typeUtilisateur["utilisateur"] . "
        ON(stage.id" . ucfirst($typeUtilisateur["utilisateur"]) . "=" . $typeUtilisateur["utilisateur"] . ".id" . ucfirst($typeUtilisateur["utilisateur"]) . ")
        LEFT JOIN " . $typeUtilisateur["autreUtilisateur"] . "
        ON(stage.id" . ucfirst($typeUtilisateur["autreUtilisateur"]) . "=" . $typeUtilisateur["autreUtilisateur"] . ".id" . ucfirst($typeUtilisateur["autreUtilisateur"]) . ")
        LEFT JOIN personne ON(" . $typeUtilisateur["autreUtilisateur"] . ".idPersonne=personne.idPersonne)
        WHERE stage.id" . ucfirst($typeUtilisateur["utilisateur"]) . "=" . $idUtilisateur . "
        AND stage.id" . $typeUtilisateur["autreUtilisateur"] . " " . $typeUtilisateur["attribue"] . "";

        return $connexion->query($sql);
    }

    public function list_sujet_disponible($typeUtilisateur)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        WHERE stage.id" . $typeUtilisateur["utilisateur"] . " " . $typeUtilisateur["attribue"] . "";

        return $connexion->query($sql);
    }

    public function auto_attribue($params, $type_values)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "UPDATE stage SET id" . ucfirst($type_values["autreUtilisateur"]) . "=? where idStage=?";

        try {
            $connexion->prepare($sql)->execute([$params->{"id" . ucfirst($type_values["autreUtilisateur"]) . ""}, $params->idStage]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return true;
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
