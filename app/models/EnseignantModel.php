<?php
require_once '../app/db/dao/enseignantDao.php';
require_once '../app/models/Personne.php';

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
    // Créer un enseignant
    public function create()
    {
        $enseignantDao = new EnseignantDao();
        return $enseignantDao->create($this);
    }

    // list des enseignants
    function list() {
        $enseignantDao = new EnseignantDao();
        $enseignants = [];
        foreach ($enseignantDao->list() as $enseignant) {
            array_push($enseignants, ["idEnseignant" => $enseignant["idEnseignant"], "nom" => $enseignant["nom"], "prenom" => $enseignant["prenom"], "email" => $enseignant["email"], "titre" => $enseignant["titre"], "specialisation" => $enseignant["specialisation"], "idPersonne" => $enseignant["idPersonne"], "valide" => $enseignant["valide"]]);
        }
        return $enseignants;
    }

    // trouver un enseignant par son identifiant
    public function get($id)
    {
        $enseignantDao = new EnseignantDao();
        $enseignant = $enseignantDao->get($id);
        if ($enseignant) {
            return ["idEnseignant" => $enseignant["idEnseignant"], "nom" => $enseignant["nom"], "prenom" => $enseignant["prenom"], "email" => $enseignant["email"], "titre" => $enseignant["titre"], "specialisation" => $enseignant["specialisation"], "idPersonne" => $enseignant["idPersonne"], "valide" => $enseignant["valide"]];
        } else {
            return [];
        }

    }
}
