<?php

class Personne
{
    protected $idPersonne;
    protected $nom;
    protected $prenom;
    protected $email;
    private $password;
    private $admin;

    function __construct($idPersonne, $nom, $prenom, $email, $password, $admin) 
    {
        $this->idPersonnePrimary = $idPersonne;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->admin = $admin;
    }

    // Setters
    public function getIdPersonne()
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
        
    }

    public function getAdmin()
    {
        
    }
}