<?php
require_once '../app/db/dao/stageDao.php';
class StageModel
{
    private $intituleProjet;
    private $nomEntreprise;
    private $adresse;
    private $idPeriode;
    private $idEtudiant;
    private $idEnseignant;
    private $attribue;
    private $valide;
    private $acommence;

    // Setters
    public function setIntituleProjet($intituleProjet)
    {
        $this->intituleProjet = $intituleProjet;
    }

    public function setNomEntreprise($nomEntreprise)
    {
        $this->nomEntreprise = $nomEntreprise;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    public function setIdPeriode($idPeriode)
    {
        $this->idPeriode = $idPeriode;
    }

    public function setIdEtudiant($idEtudiant)
    {
        $this->idEtudiant = $idEtudiant;
    }

    public function setIdEnseignant($idEnseignant)
    {
        $this->idEnseignant = $idEnseignant;
    }

    public function setIdAttribue($attribue)
    {
        $this->attribue = $attribue;
    }

    public function setValide($valide)
    {
        $this->valide = $valide;
    }

    public function setAcommence($acommence)
    {
        $this->acommence = $acommence;
    }

    // Getters
    public function getIntituleProjet()
    {
        return $this->intituleProjet;
    }

    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function getIdPeriode()
    {
        return $this->idPeriode;
    }

    public function getIdEtudiant()
    {
        return $this->idEtudiant;
    }

    public function getIdEnseignant()
    {
        return $this->idEnseignant;
    }

    public function getAttribue()
    {
        return $this->attribue;
    }

    public function getValide()
    {
        return $this->valide;
    }

    public function getAcommence()
    {
        return $this->acommence;
    }

    public function create()
    {
        $stageDao = new StageDao();
        return $stageDao->create($this);
    }

    // list des periodes
    function list() {
        $stageDao = new StageDao();
        $stages = [];
        foreach ($stageDao->list() as $stage) {
            array_push($stages, ["idPeriode" => $periode["idPeriode"], "dateDebut" => $periode["dateDebut"], "dateFin" => $periode["dateFin"], "intitule" => $periode["intitule"], "courant" => $periode["courant"]]);
        }
        return $periodes;
    }

    // trouver une periode par son identifiant
    public function get($id)
    {
        $periodeDao = new PeriodeDao();
        $periode = $periodeDao->get($id);
        if ($periode) {
            return ["idPeriode" => $periode["idPeriode"], "dateDebut" => $periode["dateDebut"], "dateFin" => $periode["dateFin"], "intitule" => $periode["intitule"], "courant" => $periode["courant"]];
        } else {
            return [];
        }

    }

}
