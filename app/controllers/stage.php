<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";
class Stage extends Controller
{

    function list() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $stage = $this->model('StageModel');
            header("Content-type: application/json");

            if (isset($_GET['idUtilisateur'])) {

                echo json_encode(array("data" => $stage->list($_GET['idUtilisateur'], $_SESSION['user_info']["data"]["type"], $_GET['attribue'], $_GET["all"]), "status" => "ok"));
                exit;
            }

        } else {
            echo json_encode(array("message" => "L'opération n'est pas autorisé", "status" => "erreur"));
            exit;
        }
    }

    public function list_sujet_disponible()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $stage = $this->model('StageModel');
            header("Content-type: application/json");

            echo json_encode(array("data" => $stage->list_sujet_disponible($_SESSION['user_info']["data"]["type"]), "status" => "ok"));

        } else {
            echo json_encode(array("message" => "L'opération n'est pas autorisé", "status" => "erreur"));
            exit;
        }
    }

    public function auto_attribue()
    {
        if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
            $params = json_decode(file_get_contents('php://input'));
            $params = $this->add_params($params);

            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $utils->verifier_les_parametres($params, $this->auto_attribue_paramettre_obligatoire());
            $stage = $this->model('StageModel');
            $stageDispo = $stage->get($params->idStage);
            if (!$stageDispo) {
                echo json_encode(
                    array("message" => "Le stage n'existe pas", "status" => "erreur")
                );
                exit;

            }

            header("Content-type: application/json");
            if (!in_array($_SESSION['user_info']["data"]["type"], ["etudiant", "enseignant"])) {
                echo json_encode(
                    array("message" => "L'utilisateur doit être soit un étudiant ou un enseignant", "status" => "erreur")
                );
                exit;
            }
            // echo json_encode(
            //     array("message" => "Le stage n'existe pas", "status" => "erreur")
            // );
            // exit;

            // $stagesDansLaPeriode = $stage->stagesDansLaPeriodePourLenseingnant($stageDispo["idPeriode"], $params->idEnseignant);

            // $nombreStage = $this->model('NombreStageModel');

            // $nombreLimite = $nombreStage->nombreStageParEnseignantParPeriode($stageDispo["idPeriode"], $params->idEnseignant);

            // if ((int) $nombreLimite["nombre"] > 0 && count($stagesDansLaPeriode) >= (int) $nombreLimite["nombre"]) {
            //     echo json_encode(
            //         array("message" => "L'enseignant a déjà atteint sa limite pour cette periode", "status" => "erreur")
            //     );
            //     exit;
            // }

            $stage = $stage->auto_attribue($params, $_SESSION['user_info']["data"]["type"]);
            if ($stage == true) {
                echo json_encode(
                    array("message" => "Le stage a été attribué", "status" => "ok")
                );
            } else {
                echo json_encode(
                    array("message" => "Une erreur s'est produite", "status" => "erreur")
                );
            }
        } else {
            echo json_encode(array("message" => "L'opération n'est pas autorisé", "status" => "erreur"));
            exit;
        }
    }

    public function get()
    {
        header("Content-type: application/json");
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            if (isset($_GET["id"])) {
                $stage = $this->model('StageModel');
                echo json_encode(array("data" => $stage->get($_GET["id"]), "status" => "ok"));
            } else {
                echo json_encode(array("message" => "L'identifiant doit etre fournis", "status" => "erreur"));
                exit;
            }
        } else {
            echo json_encode(array("message" => "L'operation n'est pas autorise", "status" => "erreur"));
            exit;
        }

    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Les données provenant de la requette
            $params = json_decode(file_get_contents('php://input'));
            $params = $this->add_params($params);
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $utils->verifier_les_parametres($params, $this->parametre_obligatoire());

            $stage = $this->model('StageModel');

            // allouer des valeurs à l'objet stage
            $stage = $this->get_stage($stage, $params);
            $stageId = $stage->create();

            header("Content-type: application/json");
            if ($stageId != null) {
                $params->id = (int) $stageId;
                echo json_encode(
                    array("data" => $params, "status" => "ok")
                );
                exit;
            } else {
                echo json_encode(
                    array("message" => "Une erreur s'est produite", "status" => "erreur")
                );
                exit;
            }

        } else {
            echo json_encode(
                array("message" => "L'operation n'est pas autorise", "status" => "erreur")
            );
            exit;
        }

    }

    public function attribue()
    {
        if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
            $params = json_decode(file_get_contents('php://input'));

            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            header("Content-type: application/json");
            if ($_SESSION['user_info']["data"]["type"] != "admin") {
                echo json_encode(
                    array("message" => "L'utilisateur doit être un admin", "status" => "erreur")
                );
                exit;
            }

            $utils->verifier_les_parametres($params, $this->attribue_paramettre_obligatoire());
            $stage = $this->model('StageModel');
            if (gettype($params->idStages) != 'array') {
                echo json_encode(
                    array("message" => "Le paramètre idStage doit être un tableau contenant la liste des identifiants des stages", "status" => "erreur")
                );
                exit;
            }
            $stageNonAttribue = $stage->list_stage_non_attribue($params->idStages, $params->idPeriode);

            if (!$stageNonAttribue) {
                echo json_encode(
                    array("message" => "Aucun stage non attribué", "status" => "erreur")
                );
                exit;
            }
            $preference = $this->model('PreferenceModel');

            $preferences = $preference->list_des_preferences_lie_au_stages(implode(",", array_map(
                fn($stage) => $stage["idStage"],
                $stageNonAttribue
            )), $params->idPeriode
            );
            $preference_existant = $this->preferencesExistant($preferences);
            $preference_nom_existant = $this->preferencesNonExistant($preferences);

            $stagesDansLaPeriode = $stage->NombrestagesDansLaPeriodePourLesseingnant($params->idPeriode,
                array_map(
                    fn($pref) => $pref["preferenceIdEnseignant"],
                    $preference_existant
                ));
            $nombreStage = $this->model('NombreStageModel');

            // $nombreLimite = $nombreStage->nombreStageParEnseignantParPeriode($stageDispo["idPeriode"], $params->idEnseignant);

            // if ((int) $nombreLimite["nombre"] > 0 && count($stagesDansLaPeriode) >= (int) $nombreLimite["nombre"]) {
            //     echo json_encode(
            //         array("message" => "L'enseignant a déjà atteint sa limite pour cette periode", "status" => "erreur")
            //     );
            //     exit;
            // }

            // $stage = $stage->auto_attribue($params, $_SESSION['user_info']["data"]["type"]);
            if ($stage == true) {
                echo json_encode(
                    array("data" => "Le stage a été attribué", "status" => "ok")
                );
            } else {
                echo json_encode(
                    array("message" => "Une erreur s'est produite", "status" => "erreur")
                );
            }
        } else {
            echo json_encode(array("message" => "L'opération n'est pas autorisé", "status" => "erreur"));
            exit;
        }

    }

    public function preferencesExistant($preferences)
    {
        return array_filter(
            $preferences,
            function ($preference) {
                return $preference["date_ajout"] != null;
            }
        );

    }

    public function preferencesNonExistant($preferences)
    {
        return array_filter(
            $preferences,
            function ($preference) {
                return $preference["date_ajout"] == null;
            }
        );

    }

    public function attributions($preferences)
    {

    }

    public function add_params($params)
    {
        if ($_SESSION['user_info']["data"]["type"] == "enseignant" && !isset($params->idEnseignant)) {
            $params->idEnseignant = $_SESSION['user_info']["data"]["id"];
        }

        if ($_SESSION['user_info']["data"]["type"] == "etudiant" && !isset($params->idEtudiant)) {
            $params->idEtudiant = $_SESSION['user_info']["data"]["id"];
        }

        return $params;
    }

    private function parametre_obligatoire()
    {
        $params = array("intituleProjet", "nomEntreprise", "adresse", "idPeriode");

        if ($_SESSION['user_info']["data"]["type"] == "enseignant") {
            array_push($params, "idEnseignant");
        }

        if ($_SESSION['user_info']["data"]["type"] == "etudiant") {
            array_push($params, "idEtudiant");
        }
        return $params;
    }

    private function auto_attribue_paramettre_obligatoire()
    {
        $params = array("idStage");

        if ($_SESSION['user_info']["data"]["type"] == "etudiant") {
            array_push($params, "idEtudiant");
        }

        if ($_SESSION['user_info']["data"]["type"] == "enseignant") {
            array_push($params, "idEnseignant");
        }
        return $params;
    }

    private function attribue_paramettre_obligatoire()
    {
        $params = array("idStages", "idPeriode");
        return $params;
    }

    private function get_stage($stage, $params)
    {
        $stage->setIntituleProjet($params->intituleProjet);
        $stage->setNomEntreprise($params->nomEntreprise);
        $stage->setAdresse($params->adresse);
        $stage->setIdPeriode($params->idPeriode);
        if (isset($params->idEtudiant)) {
            $stage->setIdEtudiant($params->idEtudiant);
        }

        if (isset($params->idEnseignant)) {
            $stage->setIdEnseignant($params->idEnseignant);
        }
        if (isset($params->attribue)) {
            $stage->setAttribue($params->attribue);
        }
        return $stage;
    }

    // private function boolean_valide($boolean)
    // {
    //     if ($boolean == "false") {
    //         return 0;
    //     }
    //     if ($boolean == "true") {
    //         return 1;
    //     }
    // }

}
