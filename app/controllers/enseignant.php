<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";

class Enseignant extends Controller
{

    function list() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $utils = new Utils();
            // vérifier si l'utilisateur est authentifier
            $utils->verifier_authentification_utilisateur();
            $enseignant = $this->model('EnseignantModel');
            header("Content-type: application/json");

            echo json_encode(array("data" => $enseignant->list(), "status" => "ok"));
        } else {
            print "L'opération n'est pas autorisé";
            exit;
        }
    }

    public function create()
    {
        $password_config = require '../userpassword.php';
        if ($_SERVER["PHP_AUTH_USER"] == $password_config["USER_NAME"] && $_SERVER["PHP_AUTH_PW"] == $password_config["USER_PASSWORD"]) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                header("Content-type: application/json");
                $utils = new Utils();

                $params = json_decode(file_get_contents('php://input'));
                // vérifier si tous les paramètres nécessaire ont été fournis
                $utils->verifier_les_parametres($params, $this->parametre_obligatoire());

                $personne = $this->model('Personne');
                $enseignant = $this->model('EnseignantModel');
                // allouer dles valeur à l'objet personne
                $personne = $this->get_personne($personne, $params);
                // créer la personne et retourne son identifiant
                $personneId = (int) $personne->create();
                // allouer des valeurs à l'objet enseignant
                $enseignant = $this->get_enseignant($enseignant, $personneId, $params);
                $enseignantId = $enseignant->create();

                if ($enseignantId != null) {
                    $params->id = (int) $enseignantId;
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
            $utils->verifier_les_parametres($params, $this->parametre_valide());
            $enseignant = $this->model('EnseignantModel');
            $personne = $this->model("Personne");
            $enseignan = $enseignant->get($params->id);
            header("Content-type: application/json");
            if (count($enseignan) > 0 && (int) $enseignan["idPersonne"] > 0) {
                $count = $personne->valider($enseignan["idPersonne"], $this->boolean_valide($params->valide));
                echo json_encode(array("count" => $count, "status" => "ok"));
            } else {
                echo json_encode(array("message" => "Paramètre incorrectes", "status" => "erreur"));
            }
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
                $enseignant = $this->model('EnseignantModel');
                echo json_encode(array("data" => $enseignant->get($_GET["id"]), "status" => "ok"));
            } else {
                print "L'identifiant doit être fournis";
                exit;
            }
        } else {
            print "L'opération n'est pas autorisé";
            exit;
        }

    }

    private function parametre_obligatoire()
    {
        return array("nom", "prenom", "email", "password", "titre", "specialisation");
    }

    private function parametre_valide()
    {
        return array("id", "valide");
    }

    private function get_personne($personne, $params)
    {
        $personne->setNom($params->nom);
        $personne->setPrenom($params->prenom);
        $personne->setEmail($params->email);
        $personne->setPassword($params->password);
        return $personne;
    }

    private function get_enseignant($enseignant, $personneId, $params)
    {
        $enseignant->setIdPersonne($personneId);
        $enseignant->setTitre($params->titre);
        $enseignant->setSpecialisation($params->specialisation);
        return $enseignant;
    }

    private function boolean_valide($boolean)
    {
        if ($boolean == "false") {
            return 0;
        }
        if ($boolean == "true") {
            return 1;
        }
    }

}
