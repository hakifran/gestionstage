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

    function list() {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM personne INNER JOIN enseignant on(personne.idPersonne=enseignant.idPersonne)";
        return $connexion->query($sql);
    }

    public function get($id)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $stmt = $connexion->prepare("SELECT * FROM personne INNER JOIN enseignant on(personne.idPersonne=enseignant.idPersonne) where idEnseignant=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function recherche_par_email($email)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $stmt = $connexion->prepare("SELECT * FROM personne INNER JOIN enseignant on(personne.idPersonne=enseignant.idPersonne) where personne.email=:email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}
