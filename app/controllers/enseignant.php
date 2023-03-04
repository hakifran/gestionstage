<?php
if(!isset($_SERVER["PHP_AUTH_USER"]))
{
    header("WWW-Authenticate: Basic realm=\"Private Area\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Désolé, vous devez vous authentifier en donnant votre nom d'utilisateur et mot de passe";
    exit;
} else {
    if(($_SERVER["PHP_AUTH_USER"] == 'admin' && $_SERVER["PHP_AUTH_PW"] == "@admin")){
        class Enseignant extends Controller
        {
            public function index($name='')
            {
                echo $name;
            }

            public function create($name='', $heheheh='')
            {
                $test =  array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");
                $json = json_encode($test);
               // print_r($_SERVER);
               // Takes raw data from the request
                $json = file_get_contents('php://input');

                // Converts it into a PHP object
                $data = json_decode($json);
               header("Content-type: application/json");
                echo $json;
            }
        }
    }else{
        header("WWW-Authenticate: Basic realm=\"Private Area\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Veuillez verifier vos identifiants";
        exit;
    }
}