<?php

class Etudiant extends Personne
{
    private $idEtudiant;
    private $numeroEtudiant;
    private $numeroNational;
    private $parcours;
    private $idPersonne;

    function __construct($idEtudiant, $numeroEtudiant, $numeroNational, $parcours, $idPersonne) 
    {
        $this->idEtudiant = $idEtudiant;
        $this->numeroEtudiant = $numeroEtudiant;
        $this->numeroNational = $numeroNational;
        $this->parcours = $parcours;
        $this->idPersonne = $idPersonne;
    }

    // Setters
    public function getIdEtudiant()
    {
        return $this->idEtudiant;
    }

    public function getNumeroEtudiant()
    {
        return $this->numeroEtudiant;
    }

    public function getNumeroNational()
    {
        return $this->numeroNational;
    }

    public function getParcours()
    {
        return $this->parcours;
    }

    public function getIdPersonne()
    {
        return $this->idPersonne;
    }

}