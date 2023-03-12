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

    public function valider($idPersonne, $valide)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "UPDATE personne SET valide=? where idPersonne=?";
        $updated = $connexion->prepare($sql);
        $updated->execute([$valide, $idPersonne]);
        return $updated->rowCount();
    }
}
