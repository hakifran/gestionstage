<?php
require_once "../app/db/basededonnee.php";

class PreferenceDao
{
    // Requette pour créer un preference
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
    // recuperer les preferences par periode
    public function preferenceParPeriode($idUtilisateur, $periode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "SELECT * FROM stage JOIN periode ON(stage.idPeriode=periode.idPeriode) JOIN preference
        ON(preference.idStage=stage.idStage) JOIN enseignant ON(enseignant.idEnseignant=preference.idEnseignant)
        WHERE enseignant.idEnseignant=" . $idUtilisateur . " AND periode.idPeriode=" . $periode . "";

        return $connexion->query($sql);
    }

    public function list_des_preferences_lie_au_stages($idStages, $idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();
        $sql = "SELECT *, enseignant.idEnseignant as preferenceIdEnseignant FROM stage JOIN periode ON(stage.idPeriode=periode.idPeriode)
        JOIN preference ON(preference.idStage=stage.idStage)
        JOIN enseignant ON(enseignant.idEnseignant=preference.idEnseignant)
        WHERE stage.idStage IN(" . $idStages . ") AND periode.idPeriode=" . $idPeriode . "
        ORDER BY date_ajout";

        return $connexion->query($sql);
    }
}
