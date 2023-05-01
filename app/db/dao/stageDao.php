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

    public function get($id)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT *, etudiantPersonne.nom as nomEtudiant, etudiantPersonne.prenom as prenomEtudiant, enseignantPersonne.nom as nomEnseignant,
        enseignantPersonne.prenom as prenomEnseignant, periode.intitule as periodeIntitule FROM `stage` JOIN periode ON(periode.idPeriode=stage.idPeriode)
        LEFT JOIN enseignant ON(stage.idEnseignant=enseignant.idEnseignant)
        LEFT JOIN etudiant ON (stage.idEtudiant=etudiant.idEtudiant)
        LEFT JOIN personne as enseignantPersonne ON(enseignant.idPersonne=enseignantPersonne.idPersonne)
        LEFT JOIN personne as etudiantPersonne ON(etudiant.idPersonne=etudiantPersonne.idPersonne)
        WHERE stage.idStage=" . $id . "";

        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function stagesDansLaPeriodePourLenseingnant($idPeriode, $idEnseignant)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN enseignant
        ON (stage.idEnseignant=enseignant.idEnseignant)
        JOIN etudiant
        ON (stage.idEtudiant=etudiant.idEtudiant)
        AND periode.idPeriode=" . $idPeriode . " AND enseignant.idEnseignant=" . $idEnseignant . "";

        return $connexion->query($sql);

    }

    public function NombrestagesDansLaPeriodePourLesseingnant($idPeriode, $idEnseignants)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT COUNT(*), idEnseignant FROM stage WHERE idEnseignant IN(" . $stages . ") and idPeriode=" . $periode . " GROUP BY idEnseignant";
        echo $sql;
        return $connexion->query($sql);

    }

    public function stageDansLaperiode($stages, $periode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN etudiant
        ON (stage.idEtudiant=etudiant.idEtudiant)
        JOIN personne ON(etudiant.idPersonne=personne.idPersonne)
        WHERE stage.idEnseignant IS NULL
        AND stage.idStage IN(" . $stages . ")
        AND stage.idPeriode = " . $periode . "";

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

    public function list_stage_non_attribue($idStages, $idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN etudiant
        ON (stage.idEtudiant=etudiant.idEtudiant)
        JOIN personne ON(etudiant.idPersonne=personne.idPersonne)
        WHERE stage.idEnseignant IS NULL
        AND stage.idStage IN(" . $idStages . ") AND periode.idPeriode=" . $idPeriode . "";

        return $connexion->query($sql);

    }

    public function auto_attribue($params, $type_values)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "UPDATE stage SET stage.id" . ucfirst($type_values["autreUtilisateur"]) . "=?, stage.attribue=? where stage.idStage=?";

        try {
            $connexion->prepare($sql)->execute([$params->{"id" . ucfirst($type_values["autreUtilisateur"]) . ""}, '1', $params->idStage]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return true;
    }

}
