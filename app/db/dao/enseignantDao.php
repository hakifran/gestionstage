<?php
require_once "../app/db/basededonnee.php";

class EnseignantDao
{

    public function create($enseignant)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "INSERT INTO enseignant (titre, specialisation, idPersonne) VALUES (?,?,?)";
        $connexion->prepare($sql)->execute([$enseignant->getTitre(), $enseignant->getSpecialisation(), $enseignant->getIdPersonne()]);
        return $connexion->lastInsertId();
    }
}