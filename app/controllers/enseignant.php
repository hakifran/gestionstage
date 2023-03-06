<?php
if (!isset($_SERVER["PHP_AUTH_USER"])) {
    header("WWW-Authenticate: Basic realm=\"Private Area\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Désolé, vous devez vous authentifier en donnant votre nom d'utilisateur et mot de passe";
    exit;
} else {
    if (($_SERVER["PHP_AUTH_USER"] == 'admin' && $_SERVER["PHP_AUTH_PW"] == "@admin")) {
        class Enseignant extends Controller
        {
            public function index($name = '')
            {
                echo $name;
            }

            function create()
            {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Les données provenant de la requette
                    $params = json_decode(file_get_contents('php://input'));
                    //$params = json_encode($params);
                    $personne = $this->model('Personne');
                    $enseignant = $this->model('EnseignantModel');

                    $personne->setNom($params->nom);
                    $personne->setPrenom($params->prenom);
                    $personne->setIdentifiant($params->identifiant);
                    $personne->setEmail($params->email);
                    $personne->setPassword($params->password);

                    $enseignant->setTitre($params->titre);
                    $enseignant->setSpecialisation($params->specialisation);
                    header("Content-type: application/json");
                    echo json_encode($params);
                } else {
                    print "L'opération n'est pas autorisé";
                    exit;
                }

            }

            function getRequestParams($jsonParams)
            {

            }
        }
    } else {
        header("WWW-Authenticate: Basic realm=\"Private Area\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Veuillez verifier vos identifiants";
        exit;
    }
}
