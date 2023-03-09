<?php
require_once "../app/services/utils.php";
if (!isset($_SERVER["PHP_AUTH_USER"])) {
    header("WWW-Authenticate: Basic realm=\"Private Area\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Désolé, vous devez vous authentifier en donnant votre nom d'utilisateur et mot de passe";
    exit;
} else {
    if (($_SERVER["PHP_AUTH_USER"] == 'admin' && $_SERVER["PHP_AUTH_PW"] == "@admin")) {
        class Etudiant extends Controller
        {

            function list() {
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    $etudiant = $this->model('EtudiantModel');
                    header("Content-type: application/json");
                    echo json_encode(array("data" => $etudiant->list(), "status" => "ok"));
                } else {
                    print "L'opération n'est pas autorisé";
                    exit;
                }
            }

            function create()
            {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                            array("message" => "Une erreur s'est produite", "status" => "error")
                        );
                    }

                } else {
                    print "L'opération n'est pas autorisé";
                    exit;
                }

            }

            function parametre_obligatoire()
            {
                return array("nom", "prenom", "identifiant", "email", "password", "numeroEtudiant", "numeroNational", "parcours");
            }

            function get_personne($personne, $params)
            {
                $personne->setNom($params->nom);
                $personne->setPrenom($params->prenom);
                $personne->setIdentifiant($params->identifiant);
                $personne->setEmail($params->email);
                $personne->setPassword($params->password);
                return $personne;
            }

            function get_etudiant($etudiant, $personneId, $params)
            {
                $etudiant->setIdPersonne($personneId);
                $etudiant->setNumeroEtudiant($params->numeroEtudiant);
                $etudiant->setNumeroNational($params->numeroNational);
                $etudiant->setParcours($params->parcours);
                return $etudiant;
            }

        }
    } else {
        header("WWW-Authenticate: Basic realm=\"Private Area\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Veuillez verifier vos identifiants";
        exit;
    }
}
