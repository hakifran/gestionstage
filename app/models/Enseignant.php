<?php

class Enseignant extends Personne
{
    protected $idEnseignant;
    protected $titre;
    protected $specialisation;
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
    public function getIdEnseignant()
    {
        return $this->idEnseignant;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getSpecialisation()
    {
        return $this->specialisation;
    }

    public function getIdPersonne()
    {
        return $this->idPersonne;
    }

}