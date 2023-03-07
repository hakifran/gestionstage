<?php
require_once '../app/db/dao/enseignantDao.php';
class EnseignantModel extends Personne
{
    protected $idEnseignant;
    protected $titre;
    protected $specialisation;
    protected $idPersonne;

    // getters
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

    // Setters
    public function setIdEnseignant($idEnseignant)
    {
        $this->idEnseignant = $idEnseignant;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function setSpecialisation($specialisation)
    {
        $this->specialisation = $specialisation;
    }

    public function setIdPersonne($idPersonne)
    {
        $this->idPersonne = $idPersonne;
    }

    public function create()
    {
        $enseignantDao = new EnseignantDao();
        return $enseignantDao->create($this);
    }

}
