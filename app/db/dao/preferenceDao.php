<?php
require_once "../app/db/basededonnee.php";

class PreferenceDao
{

    public function create($preference, $stages)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql_params = [];
        $sql = "INSERT INTO preference (idStage, idEnseignant, date_ajout) VALUES ";
        foreach ($stages as $stage) {
            array_push($sql_params, "(" . $stage . "," . $preference->getIdEnseignant() . ",now())");
        }

        try {
            $connexion->prepare($sql . implode(",", $sql_params))->execute();
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }
        return $connexion->lastInsertId();
    }

    // function list($idUtilisateur, $typeUtilisateur) {
    //     $bd = new Basededonnee();
    //     $connexion = $bd->connexion();

    //     $sql = "SELECT * FROM `stage`
    //     JOIN periode ON(periode.idPeriode=stage.idPeriode)
    //     JOIN " . $typeUtilisateur["utilisateur"] . "
    //     ON(stage.id" . ucfirst($typeUtilisateur["utilisateur"]) . "=" . $typeUtilisateur["utilisateur"] . ".id" . ucfirst($typeUtilisateur["utilisateur"]) . ")
    //     LEFT JOIN " . $typeUtilisateur["autreUtilisateur"] . "
    //     ON(stage.id" . ucfirst($typeUtilisateur["autreUtilisateur"]) . "=" . $typeUtilisateur["autreUtilisateur"] . ".id" . ucfirst($typeUtilisateur["autreUtilisateur"]) . ")
    //     LEFT JOIN personne ON(" . $typeUtilisateur["autreUtilisateur"] . ".idPersonne=personne.idPersonne)
    //     WHERE stage.id" . ucfirst($typeUtilisateur["utilisateur"]) . "=" . $idUtilisateur . "
    //     AND stage.id" . $typeUtilisateur["autreUtilisateur"] . " " . $typeUtilisateur["attribue"] . "";

    //     return $connexion->query($sql);
    // }

    // public function stageDansLaperiode($stages, $periode)
    // {
    //     $bd = new Basededonnee();
    //     $connexion = $bd->connexion();

    //     $sql = "SELECT * FROM `stage`
    //     JOIN periode ON(periode.idPeriode=stage.idPeriode)
    //     JOIN etudiant
    //     ON (stage.idEtudiant=etudiant.idEtudiant)
    //     JOIN personne ON(etudiant.idPersonne=personne.idPersonne)
    //     WHERE stage.idEnseignant IS NULL
    //     AND stage.idStage IN(" . $stages . ")
    //     AND stage.idPeriode = " . $periode . "";

    //     return $connexion->query($sql);
    // }

    // public function list_sujet_disponible($typeUtilisateur)
    // {
    //     $bd = new Basededonnee();
    //     $connexion = $bd->connexion();

    //     $sql = "SELECT * FROM `stage`
    //     JOIN periode ON(periode.idPeriode=stage.idPeriode)
    //     WHERE stage.id" . $typeUtilisateur["utilisateur"] . " " . $typeUtilisateur["attribue"] . "";

    //     return $connexion->query($sql);
    // }

    // public function auto_attribue($params, $type_values)
    // {
    //     $bd = new Basededonnee();
    //     $connexion = $bd->connexion();
    //     $sql = "UPDATE stage SET id" . ucfirst($type_values["autreUtilisateur"]) . "=? where idStage=?";

    //     try {
    //         $connexion->prepare($sql)->execute([$params->{"id" . ucfirst($type_values["autreUtilisateur"]) . ""}, $params->idStage]);
    //     } catch (Exception $e) {
    //         echo json_encode(
    //             array("message" => $e->getMessage(), "status" => "erreur")
    //         );
    //         exit;
    //     }

    //     return true;
    // }

    // public function get($id)
    // {
    //     $bd = new Basededonnee();
    //     $connexion = $bd->connexion();

    //     $stmt = $connexion->prepare("SELECT * FROM periode where idPeriode=:id");
    //     $stmt->execute(['id' => $id]);
    //     return $stmt->fetch();
    // }
}
