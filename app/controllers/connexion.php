<?php
require_once "../app/services/utils.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

if (!isset($_SERVER["PHP_AUTH_USER"])) {
    header("WWW-Authenticate: Basic realm=\"Private Area\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Désolé, vous devez vous authentifier en donnant votre nom d'utilisateur et mot de passe";
    exit;
} else {
    if (($_SERVER["PHP_AUTH_USER"] == 'admin' && $_SERVER["PHP_AUTH_PW"] == "@admin")) {
        class connexion extends Controller
        {
            public function login()
            {
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
                            $etudiant = $this->model('EnseignantModel');
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
                        echo json_encode(array("status" => "error", "message" => "L'addresse email ou le mot de password n'est pas correcte"));
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
                                echo json_encode(array("status" => "error", "message" => "L'utilisateur non encore valide par l'admin"));
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
                            session_start();
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
            }

            function parametre_obligatoire()
            {
                return array("email", "password", "typeUtilisateur");
            }
        }
    } else {
        header("WWW-Authenticate: Basic realm=\"Private Area\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Veuillez verifier vos identifiants";
        exit;
    }
}