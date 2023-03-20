<?php
require_once '../app/db/dao/personneDao.php';
class Personne
{
    protected $idPersonne;
    protected $nom;
    protected $prenom;
    protected $email;
    private $password;
    private $admin;
    private $valide;

    // getters
    private function getIdPersonne()
    {
        return $this->idPersonne;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function getValide()
    {
        return $this->valide;
    }

    // setters
    private function setIdPersonne()
    {
        $this->idPersonne = $idPersonne;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    public function setValide($valide)
    {
        $this->valide = $valide;
    }

    public function create()
    {
        $personneDao = new PersonneDao();
        return $personneDao->create($this);
    }

    public function create_admin()
    {
        $personneDao = new PersonneDao();
        return $personneDao->create_admin($this);
    }

    // valider l'inscription d'un utilisateur
    public function valider($idPersonne, $valide)
    {
        $personneDao = new PersonneDao();
        $count = $personneDao->valider($idPersonne, $valide);
        return $count;
    }

    // trouver un admin par son email
    public function recherche_admin($email)
    {
        $personneDao = new PersonneDao();
        $personne = $personneDao->recherche_admin($email);
        if ($personne) {
            return ["idPersonne" => $personne["idPersonne"], "nom" => $personne["nom"], "prenom" => $personne["prenom"], "email" => $personne["email"], "password" => $personne["password"], "admin" => $personne["admin"], "valide" => $personne["valide"]];
        } else {
            return [];
        }
    }

}
