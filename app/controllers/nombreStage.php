<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";
class NombreStage extends Controller
{

    function list() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $nombreStage = $this->model('NombreStageModel');
            header("Content-type: application/json");
            if (!$_GET["idUtilisateur"]) {
                echo json_encode(array("message" => "L'identifiant de l'utilisateur manque", "status" => "erreur"));
                exit;
            }

            if ($_GET["idUtilisateur"] != $_SESSION['user_info']["data"]["id"]) {
                echo json_encode(array("message" => "ce n'est pas l'utilisateur connecté", "status" => "erreur"));
                exit;
            }

            echo json_encode(array("data" => $nombreStage->list($_GET["idUtilisateur"]), "status" => "ok"));

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
                $nombreStage = $this->model('NombreStageModel');
                echo json_encode(array("data" => $nombreStage->get($_GET["id"]), "status" => "ok"));
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
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $utils->verifier_les_parametres($params, $this->parametre_obligatoire());

            $nombreStage = $this->model('NombreStageModel');

            // allouer des valeurs à l'objet stage
            if ($_SESSION['user_info']["data"]["type"] != "enseignant") {
                echo json_encode(
                    array("ce n'est pas un enseignant" => $params, "status" => "erreur")
                );
                exit;
            }

            if ($params->idEnseignant != $_SESSION['user_info']["data"]["id"]) {
                echo json_encode(
                    array("ce n'est pas l'utilisateur connecté" => $params, "status" => "erreur")
                );
                exit;
            }

            $nombreStage = $this->get_nombre_stage($nombreStage, $params);
            $nombreStageId = $nombreStage->create();

            header("Content-type: application/json");
            if ($nombreStageId != null) {
                $params->id = (int) $nombreStageId;
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

    private function parametre_obligatoire()
    {
        return array("nombre", "idEnseignant", "idPeriode");
    }

    private function get_nombre_stage($nombreStage, $params)
    {
        $nombreStage->setNombre($params->nombre);
        $nombreStage->setIdEnseignant($params->idEnseignant);
        $nombreStage->setIdPeriode($params->idPeriode);

        return $nombreStage;
    }

}
