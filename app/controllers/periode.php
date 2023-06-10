<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";
class Periode extends Controller
{

    function list() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $periode = $this->model('PeriodeModel');
            header("Content-type: application/json");
            echo json_encode(array("data" => $periode->list(), "status" => "ok"));
        } else {
            print "L'opération n'est pas autorisé";
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
                $periode = $this->model('PeriodeModel');
                echo json_encode(array("data" => $periode->get($_GET["id"]), "status" => "ok"));
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

            $periode = $this->model('PeriodeModel');

            // allouer des valeurs à l'objet periode
            $periode = $this->get_periode($periode, $params);
            $periodeId = $periode->create();

            header("Content-type: application/json");
            if ($periodeId != null) {
                $params->id = (int) $periodeId;
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

    public function update()
    {
        if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
            header("Content-type: application/json");
            if (isset($_GET["idPeriode"])) {
                // Les données provenant de la requette
                $params = json_decode(file_get_contents('php://input'));
                $utils = new Utils();
                // vérifier si l'utilisateur est authentifier
                $utils->verifier_authentification_utilisateur();
                $utils->verifier_les_parametres($params, $this->parametre_obligatoire());

                $periode = $this->model('PeriodeModel');

                // allouer des valeurs à l'objet periode
                $periode = $this->get_periode($periode, $params);

                $periodeId = $periode->update($_GET["idPeriode"]);

                header("Content-type: application/json");
                if ($periodeId != null) {
                    $params->id = (int) $periodeId;
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
                    array("message" => "L'identifiant de la période doit être fournie", "status" => "erreur")
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

    private function parametre_obligatoire()
    {
        return array("dateDebut", "dateFin", "intitule", "courant");
    }

    private function get_periode($periode, $params)
    {
        $periode->setDateDebut($params->dateDebut);
        $periode->setDateFin($params->dateFin);
        $periode->setIntitule($params->intitule);
        $periode->setCourant($params->courant);
        return $periode;
    }

}
