<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../..");
$dotenv->load();

class Etudiant extends Controller
{

    function list() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            if ($_SESSION['user_info']["data"]["type"] != "admin") {
                echo json_encode(array("message" => "L'utilisateur doit être un administrateur", "status" => "erreur"));
                exit;
            }
            $etudiant = $this->model('EtudiantModel');
            header("Content-type: application/json");
            echo json_encode(array("data" => $etudiant->list(), "status" => "ok"));
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
            if ($_SESSION['user_info']["data"]["type"] != "admin" && $_SESSION['user_info']["data"]["type"] != "etudiant") {
                echo json_encode(array("message" => "L'utilisateur doit être un admin ou étudiant", "status" => "erreur"));
                exit;
            }

            if (isset($_GET["id"])) {
                $etudiant = $this->model('EtudiantModel');
                echo json_encode(array("data" => $etudiant->get($_GET["id"]), "status" => "ok"));
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

        if ($_SERVER["PHP_AUTH_USER"] == $_ENV["USER_NAME"] && $_SERVER["PHP_AUTH_PW"] == $_ENV["USER_PASSWORD"]) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                header("Content-type: application/json");
                // Les données provenant de la requette
                $params = json_decode(file_get_contents('php://input'));
                $utils = new Utils();

                $utils->verifier_les_parametres($params, $this->parametre_obligatoire());
                $personne = $this->model('Personne');
                $etudiant = $this->model('EtudiantModel');
                // allouer de valeur à l'objet personne
                $personne = $this->get_personne($personne, $params);
                // créer la personne et retourne son identifiant
                $personneId = (int) $personne->create();
                // allouer des valeurs à l'objet personne
                $etudiant = $this->get_etudiant($etudiant, $personneId, $params);
                $etudiantId = $etudiant->create();

                header("Content-type: application/json");
                if ($etudiantId != null) {
                    $params->id = (int) $etudiantId;
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

        } else {
            echo json_encode(
                array("message" => "Veuillez verifier vos identifiants", "status" => "erreur")
            );
            exit;
        }

    }

    public function valider()
    {
        if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
            $params = json_decode(file_get_contents('php://input'));
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();

            if ($_SESSION['user_info']["data"]["type"] != "admin") {
                echo json_encode(array("message" => "L'utilisateur doit être un administrateur", "status" => "erreur"));
                exit;
            }
            $utils->verifier_les_parametres($params, $this->parametre_valide());
            $etudiant = $this->model('EtudiantModel');
            $personne = $this->model("Personne");
            $etudian = $etudiant->get($params->id);

            header("Content-type: application/json");
            if (count($etudian) > 0 && (int) $etudian["idPersonne"] > 0) {
                $count = $personne->valider($etudian["idPersonne"], $this->boolean_valide($params->valide));
                echo json_encode(array("count" => $count, "status" => "ok"));
            } else {
                echo json_encode(array("message" => "Paramètre incorrectes", "status" => "erreur"));
            }
        } else {
            print "L'operation n'est pas autorise";
            exit;
        }
    }

    public function parametre_obligatoire()
    {
        return array("nom", "prenom", "email", "password", "numeroEtudiant", "numeroNational", "parcours");
    }

    public function parametre_valide()
    {
        return array("id", "valide");
    }

    public function get_personne($personne, $params)
    {
        $personne->setNom($params->nom);
        $personne->setPrenom($params->prenom);
        $personne->setEmail($params->email);
        $personne->setPassword($params->password);
        return $personne;
    }

    public function get_etudiant($etudiant, $personneId, $params)
    {
        $etudiant->setIdPersonne($personneId);
        $etudiant->setNumeroEtudiant($params->numeroEtudiant);
        $etudiant->setNumeroNational($params->numeroNational);
        $etudiant->setParcours($params->parcours);
        return $etudiant;
    }

    public function boolean_valide($boolean)
    {
        if ($boolean == "false") {
            return 0;
        }
        if ($boolean == "true") {
            return 1;
        }
    }

}
