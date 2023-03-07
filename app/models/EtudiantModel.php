<?php
require_once '../app/db/dao/etudiantDao.php';
class EtudiantModel extends Personne
{
    private $idEtudiant;
    private $numeroEtudiant;
    private $numeroNational;
    private $parcours;
    protected $idPersonne;

    // Setters
    public function setIdEtudiant($idEtudiant)
    {
        $this->idEtudiant = $idEtudiant;
    }

    public function setNumeroEtudiant($numeroEtudiant)
    {
        $this->numeroEtudiant = $numeroEtudiant;
    }

    public function setNumeroNational($numeroNational)
    {
        $this->numeroNational = $numeroNational;
    }

    public function setParcours($parcours)
    {
        $this->parcours = $parcours;
    }

    public function setIdPersonne($idPersonne)
    {
        $this->idPersonne = $idPersonne;
    }

    // Getters
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

    public function create()
    {
        $etudiantDao = new EtudiantDao();
        return $etudiantDao->create($this);
    }

}