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

    function list() {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM personne INNER JOIN etudiant on(personne.idPersonne=etudiant.idPersonne)";
        return $connexion->query($sql);
    }

    public function get($id)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $stmt = $connexion->prepare("SELECT * FROM personne INNER JOIN etudiant on(personne.idPersonne=etudiant.idPersonne) where idEtudiant=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
