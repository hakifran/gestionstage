<?php
require_once "../app/db/basededonnee.php";

class PersonneDao
{

    public function create($personne)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "INSERT INTO personne (nom, prenom, email, password) VALUES (?,?,?,?)";
        $passwrd_hash = password_hash($personne->getPassword(), PASSWORD_DEFAULT);
        try {
            $connexion->prepare($sql)->execute([$personne->getNom(), $personne->getPrenom(), $personne->getEmail(), $passwrd_hash]);
        } catch (Exception $e) {
            echo json_encode(
                array("message" => $e->getMessage(), "status" => "error")
            );
            exit;
        }
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

    public function recherche_admin($email)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $stmt = $connexion->prepare("SELECT * FROM personne where email=:email AND admin=1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}
