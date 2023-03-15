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
        return array("dateDebut", "dateFin", "intitule", "courant");
    }

    // private function parametre_valide()
    // {
    //     return array("id", "valide");
    // }

    private function get_periode($periode, $params)
    {
        $periode->setDateDebut($params->dateDebut);
        $periode->setDateFin($params->dateFin);
        $periode->setIntitule($params->intitule);
        $periode->setCourant($params->courant);
        return $periode;
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
