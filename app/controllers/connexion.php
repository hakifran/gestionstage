<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

class connexion extends Controller
{

    public function login()
    {
        if (!isset($_SERVER["PHP_AUTH_USER"])) {
            header("WWW-Authenticate: Basic realm=\"Private Area\"");
            header("HTTP/1.0 401 Unauthorized");
            print "Désolé, vous devez vous authentifier en donnant votre nom d'utilisateur et mot de passe";
            exit;
        } else {
            $password_config = require '../userpassword.php';
            if (($_SERVER["PHP_AUTH_USER"] == $password_config["USER_NAME"] && $_SERVER["PHP_AUTH_PW"] == $password_config["USER_PASSWORD"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $params = json_decode(file_get_contents('php://input'));
                    $utils = new Utils();
                    $utils->verifier_les_parametres($params, $this->parametre_obligatoire());
                    $email = $params->email;
                    $password = $params->password;
                    $typeUtilisateur = $params->typeUtilisateur;
                    $utilisateur = null;
                    $id = null;
                    switch ($typeUtilisateur) {
                        case "etudiant":
                            $etudiant = $this->model('EtudiantModel');
                            $utilisateur = $etudiant->recherche_par_email($email);
                            $id = $utilisateur["idEtudiant"];
                            break;
                        case "enseignant":
                            $enseignant = $this->model('EnseignantModel');
                            $utilisateur = $enseignant->recherche_par_email($email);
                            $id = $utilisateur["idEnseignant"];
                            break;
                        case "admin":
                            $personne = $this->model('Personne');
                            $admin = true;
                            $utilisateur = $personne->recherche_admin($email);
                            $id = $utilisateur["idPersonne"];
                            break;
                    }
                    if ($utilisateur == null || count($utilisateur) < 1) {
                        echo json_encode(array("status" => "erreur", "message" => "L'addresse email ou le mot de password n'est pas correcte"));
                        exit;
                    } else {
                        $emailUtilisateur = $utilisateur["email"];

                        $passwordUtilisateur = $utilisateur["password"];
                        $valideUtilisateur = $utilisateur["valide"];
                        $nomUtilisateur = $utilisateur["nom"];
                        $prenomUtilisateur = $utilisateur["prenom"];
                        $idUtilisateur = $id;
                        if (password_verify($password, $passwordUtilisateur)) {
                            if ($valideUtilisateur != 1) {
                                echo json_encode(array("status" => "erreur", "message" => "L'utilisateur non encore valide par l'admin"));
                                exit;
                            }
                            $payload = [
                                "iss" => "localhost",
                                "aud" => "localhost",
                                "exp" => time() + 20000,
                                "data" => [
                                    'id' => $idUtilisateur,
                                    'nom' => $nomUtilisateur,
                                    'prenom' => $prenomUtilisateur,
                                    'email' => $emailUtilisateur,
                                    'type' => $typeUtilisateur,
                                ],
                            ];
                            $secret_key = "etude thematique";
                            $jwt = JWT::encode($payload, $secret_key, 'HS256');
                            $_SESSION['user_info'] = $payload;
                            echo json_encode([
                                'statu' => 'ok',
                                'jwt' => $jwt,
                                'message' => 'connexion reussi',
                            ]);
                        } else {
                            echo json_encode([
                                'statu' => 'error',
                                'message' => "L'email ou le mot de pass invalide",
                            ]);
                        }
                    }
                } else {
                    echo array([
                        'statu' => 'error',
                        'message' => "L'opération n'est pas possible",
                    ]);
                    exit;
                }
            } else {
                header("WWW-Authenticate: Basic realm=\"Private Area\"");
                header("HTTP/1.0 401 Unauthorized");
                print "Veuillez verifier vos identifiants";
                exit;
            }
        }
    }

    public function create_admin()
    {
        if (!isset($_SERVER["PHP_AUTH_USER"])) {
            header("WWW-Authenticate: Basic realm=\"Private Area\"");
            header("HTTP/1.0 401 Unauthorized");
            print "Désolé, vous devez vous authentifier en donnant votre nom d'utilisateur et mot de passe";
            exit;
        } else {
            $password_config = require '../userpassword.php';
            if (($_SERVER["PHP_AUTH_USER"] == $password_config["ADMIN_NAME"] && $_SERVER["PHP_AUTH_PW"] == $password_config["ADMIN_PASSWORD"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    header("Content-type: application/json");
                    $utils = new Utils();

                    $params = json_decode(file_get_contents('php://input'));
                    // vérifier si tous les paramètres nécessaire ont été fournis
                    $utils->verifier_les_parametres($params, $this->parametre_obligatoire_admin());

                    $personne = $this->model('Personne');
                    // allouer dles valeur à l'objet personne
                    $personne = $this->get_personne($personne, $params);
                    // créer la personne et retourne son identifiant
                    $personneId = (int) $personne->create_admin();

                    if ($personneId != null) {
                        $params->id = (int) $personneId;
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
                header("WWW-Authenticate: Basic realm=\"Private Area\"");
                header("HTTP/1.0 401 Unauthorized");
                echo json_encode(
                    array("message" => "Veuillez verifier vos identifiants", "status" => "erreur")
                );
                exit;
            }
        }
    }

    public function parametre_obligatoire()
    {
        return array("email", "password", "typeUtilisateur");
    }

    public function parametre_obligatoire_admin()
    {
        return array("nom", "prenom", "email", "password", "admin", "valide");
    }

    private function get_personne($personne, $params)
    {
        $personne->setNom($params->nom);
        $personne->setPrenom($params->prenom);
        $personne->setEmail($params->email);
        $personne->setPassword($params->password);
        $personne->setAdmin($params->admin);
        $personne->setValide($params->valide);
        return $personne;
    }
}
