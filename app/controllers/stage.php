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
                echo json_encode(array("data" => $stage->list($_GET['idUtilisateur'], $_SESSION['user_info']["data"]["type"], $_GET['attribue']), "status" => "ok"));
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
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $utils->verifier_les_parametres($params, $this->auto_attribue_paramettre_obligatoire());
            $stage = $this->model('StageModel');
            header("Content-type: application/json");
            $stage = $stage->auto_attribue($params, $_SESSION['user_info']["data"]["type"]);
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
            header("Content-type: application/json");
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
            } else {
                echo json_encode(
                    array("message" => "Une erreur s'est produite", "status" => "erreur")
                );
            }

        } else {
            echo json_encode(
                array("message" => "L'operation n'est pas autorise", "status" => "erreur")
            );
            exit;
        }

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

    // public function valider()
    // {
    //     if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
    //         $params = json_decode(file_get_contents('php://input'));
    //         $utils = new Utils();
    //         // vérifier si l'utilisateur est authentifier
    //         $utils->verifier_authentification_utilisateur();

    //         $utils->verifier_les_parametres($params, $this->parametre_valide());
    //         $etudiant = $this->model('EtudiantModel');
    //         $personne = $this->model("Personne");
    //         $etudian = $etudiant->get($params->id);
    //         header("Content-type: application/json");
    //         if (count($etudian) > 0 && (int) $etudian["idPersonne"] > 0) {
    //             $count = $personne->valider($etudian["idPersonne"], $this->boolean_valide($params->valide));
    //             echo json_encode(array("count" => $count, "status" => "ok"));
    //         } else {
    //             echo json_encode(array("message" => "Paramètre incorrectes", "status" => "erreur"));
    //         }
    //     } else {
    //         print "L'operation n'est pas autorise";
    //         exit;
    //     }
    // }

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
            array_push($params, "idEnseignant");
        }

        if ($_SESSION['user_info']["data"]["type"] == "enseignant") {
            array_push($params, "idEtudiant");
        }
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
