<?php
require_once '../app/db/dao/personneDao.php';
class Personne
{
    protected $idPersonne;
    protected $nom;
    protected $prenom;
    protected $identifiant;
    protected $email;
    private $password;
    private $admin;

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

    // setters
    private function setIdPersonne()
    {
        $this->idPersonne = $idPersonne;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;
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

    public function create()
    {
        $personneDao = new PersonneDao();
        return $personneDao->create($this);
    }
}
