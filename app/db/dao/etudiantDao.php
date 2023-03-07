<?php
require_once "../app/db/basededonnee.php";

class EtudiantDao
{

    public function create($etudiant)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "INSERT INTO etudiant (numeroEtudiant, numeroNational, parcours, idPersonne) VALUES (?,?,?,?)";
        $connexion->prepare($sql)->execute([$etudiant->getNumeroEtudiant(), $etudiant->getNumeroNational(), $etudiant->getParcours(), $etudiant->getIdPersonne()]);
        return $connexion->lastInsertId();
    }
}
