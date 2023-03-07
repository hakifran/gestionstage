<?php
require_once "../app/db/basededonnee.php";

class PersonneDao
{

    public function create($personne)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "INSERT INTO personne (nom, prenom, email, password) VALUES (?,?,?,?)";
        $connexion->prepare($sql)->execute([$personne->getNom(), $personne->getPrenom(), $personne->getEmail(), $personne->getPassword()]);
        return $connexion->lastInsertId();
    }
}
