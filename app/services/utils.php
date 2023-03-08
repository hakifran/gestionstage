<?php

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
}
