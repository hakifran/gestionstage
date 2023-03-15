<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

session_start();
class Utils
{
    // vérification de l'existance des parametres obligatoires
    public function verifier_les_parametres($params, $parametre_obligatoire)
    {
        foreach ($parametre_obligatoire as $param) {
            if (!isset($params->{$param})) {
                print "Le parametre " . $param . " n'est pas disponible";
                exit;
            }
        }
    }

    public function verifier_authentification_utilisateur()
    {
        $tousLesHeaders = getallheaders();
        $jwt_session = isset($_SESSION["user_info"]) ? $_SESSION["user_info"] : false;
        $secret_key = "etude thematique";

        if (!$jwt_session) {
            echo json_encode(
                array("message" => "Pas de connexion possible", "status" => "erreur")
            );
            exit;
        }

        try {
            $jwt = JWT::decode($tousLesHeaders["Authorization"], new Key($secret_key, 'HS256'));
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "erreur")
            );
            exit;
        }

        $this->verifier_parametres_authentification($jwt, $jwt_session);
    }

    public function verifier_parametres_authentification($jwt, $jwt_session)
    {
        $jwt_data = (array) $jwt->data;
        $jwt_data_session = $jwt_session["data"];
        if ($jwt_data != $jwt_data_session) {
            print "hello";
        }
    }
}
