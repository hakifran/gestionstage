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

        $sql = "SELECT *, stage.valide as stage_valide FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN " . $typeUtilisateur["utilisateur"] . "
        ON(stage.id" . ucfirst($typeUtilisateur["utilisateur"]) . "=" . $typeUtilisateur["utilisateur"] . ".id" . ucfirst($typeUtilisateur["utilisateur"]) . ")
        LEFT JOIN " . $typeUtilisateur["autreUtilisateur"] . "
        ON(stage.id" . ucfirst($typeUtilisateur["autreUtilisateur"]) . "=" . $typeUtilisateur["autreUtilisateur"] . ".id" . ucfirst($typeUtilisateur["autreUtilisateur"]) . ")
        LEFT JOIN personne ON(" . $typeUtilisateur["autreUtilisateur"] . ".idPersonne=personne.idPersonne)
        WHERE stage.id" . ucfirst($typeUtilisateur["utilisateur"]) . "=" . $idUtilisateur . "
        " . (isset($typeUtilisateur["attribue"]) ? "AND stage.id" . $typeUtilisateur["autreUtilisateur"] . " " . $typeUtilisateur["attribue"] : "");

        return $connexion->query($sql);
    }

    public function list_par_periode($idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT *, stage.valide as stage_valide FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN etudiant on(stage.idEtudiant=etudiant.idEtudiant)
        JOIN personne on(etudiant.idPersonne=personne.idPersonne)
        WHERE periode.idPeriode=" . $idPeriode . "";

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

        $sql = "SELECT COUNT(*) nombre, idEnseignant FROM stage WHERE idEnseignant IN(" . $idEnseignants . ") and idPeriode=" . $idPeriode . " GROUP BY idEnseignant";

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
        WHERE stage.id" . $typeUtilisateur["utilisateur"] . " IS NULL AND stage.id" . $typeUtilisateur["autreUtilisateur"] . " IS NOT NULL";

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

    public function list_stage_non_valider($idStages, $idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM `stage`
        JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN etudiant ON (stage.idEtudiant=etudiant.idEtudiant)
        JOIN enseignant ON(stage.idEnseignant=enseignant.idEnseignant)
        JOIN personne ON(etudiant.idPersonne=personne.idPersonne)
        WHERE stage.idEnseignant IS NOT NULL
        AND stage.valide IS NULL
        AND stage.idStage IN(" . $idStages . ") AND periode.idPeriode=" . $idPeriode . "";

        return $connexion->query($sql);

    }
    public function auto_attribue($params, $type_values)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "UPDATE stage SET stage.id" . ucfirst($type_values["utilisateur"]) . "=?, stage.attribue=? where stage.idStage=?";

        try {
            $connexion->prepare($sql)->execute([$params->{"id" . ucfirst($type_values["utilisateur"]) . ""}->id, '1', $params->idStage]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return true;
    }

    public function attribue($idStage, $idEnseignant)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "UPDATE stage SET stage.idEnseignant=?, stage.attribue=? where stage.idStage=?";

        try {
            $connexion->prepare($sql)->execute([$idEnseignant, '1', $idStage]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return true;
    }

    public function valider($valeursAvalider)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "";
        foreach ($valeursAvalider as $stageAvalider) {

            $sql .= "UPDATE stage SET stage.valide=" . (string) $stageAvalider["valide"] . " where stage.idStage=" . $stageAvalider["idStage"] . ";";
        }

        try {
            $connexion->prepare($sql)->execute();
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        return true;
    }

    public function list_tous_attribue_et_non_attribue_et_par_periode($attribue_no_attribue, $idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "SELECT *, personneEtudiant.nom as nomEtudiant, personneEtudiant.prenom as prenomEtudiant,
        personneEnseignant.nom as nomEnseignant, personneEnseignant.prenom as prenomEnseignant,
        stage.valide as stageValider
        FROM stage JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN etudiant ON (etudiant.idEtudiant=stage.idEtudiant) JOIN personne as personneEtudiant
        ON(personneEtudiant.idPersonne=etudiant.idPersonne)
        " . ($attribue_no_attribue == '' || $attribue_no_attribue == 'non' ? 'LEFT JOIN' : 'JOIN') . " enseignant
        ON(enseignant.idEnseignant=stage.idEnseignant) " . ($attribue_no_attribue == '' || $attribue_no_attribue == 'non' ? 'LEFT JOIN' : 'JOIN') . "
        personne as personneEnseignant ON(personneEnseignant.idPersonne=enseignant.idPersonne)
        " . ($attribue_no_attribue != '' || $idPeriode != '' ? ' WHERE
        ' . ($attribue_no_attribue != '' ? '(stage.idEnseignant IS
        ' . ($attribue_no_attribue == 'non' ? 'NULL AND stage.attribue IS NULL OR stage.attribue=0)' : 'NOT NULL AND stage.attribue IS NOT NULL AND stage.attribue=1)') : '')
            . '' . ($idPeriode != '' ? ($attribue_no_attribue != '' ? ' AND' : '') . ' stage.idPeriode=' . $idPeriode : '') : '') . "";

        return $connexion->query($sql);

    }

    public function list_tous_valide_et_non_valide_et_par_periode($valider_non_valider, $idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "SELECT *, personneEtudiant.nom as nomEtudiant, personneEtudiant.prenom as prenomEtudiant,
        personneEnseignant.nom as nomEnseignant, personneEnseignant.prenom as prenomEnseignant,
        stage.valide as stageValider
        FROM stage JOIN periode ON(periode.idPeriode=stage.idPeriode)
        JOIN etudiant ON (etudiant.idEtudiant=stage.idEtudiant) JOIN personne as personneEtudiant
        ON(personneEtudiant.idPersonne=etudiant.idPersonne) JOIN enseignant
        ON (enseignant.idEnseignant=stage.idEnseignant) JOIN personne as personneEnseignant
        ON(personneEnseignant.idPersonne=enseignant.idPersonne)
        " . ($valider_non_valider != '' || $idPeriode != '' ? ' WHERE
        ' . ($valider_non_valider != '' ? '(stage.valide IS
        ' . ($valider_non_valider == 'non' ? 'NULL OR stage.valide=0)' : 'NOT NULL AND stage.valide IS NOT NULL AND stage.valide=1)') : '')
            . '' . ($idPeriode != '' ? ($valider_non_valider != '' ? ' AND' : '') . ' stage.idPeriode=' . $idPeriode : '') : '') . "";

        return $connexion->query($sql);

    }

}
