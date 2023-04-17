<?php
require_once '../app/db/dao/nombreStageDao.php';
class NombreStageModel
{
    private $stageIds;
    private $idEnseignant;
    private $dateAjout;

    // Setters
    public function setStageIds($stageIds)
    {
        $this->stageIds = $stageIds;
    }

    public function setIdEnseignant($idEnseignant)
    {
        $this->idEnseignant = $idEnseignant;
    }

    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    // Getters
    public function getStageIds()
    {
        return $this->stageIds;
    }

    public function getIdEnseignant()
    {
        return $this->idEnseignant;
    }

    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    public function create()
    {
        $preferenceDao = new PreferenceDao();
        return $preferenceDao->create($this);
    }

    // list des periodes
    function list($idUtilisateur) {
        $preferenceDao = new PreferenceDao();
        $preferences = [];
        foreach ($preferenceDao->list($idUtilisateur) as $preference) {
            array_push($nombreStages, ["idNombreStage" => $nombreStage["idNombreStage"], "periode" => $nombreStage["intitule"], "debut" => $nombreStage["dateDebut"], "fin" => $nombreStage["dateFin"], "nombre" => $nombreStage["nombre"]]);
        }
        return $nombreStages;
    }

    // trouver une periode par son identifiant
    public function get($id)
    {
        $nombreStageDao = new NombreStageDao();
        $nombreStage = $nombreStageDao->get($id);
        if ($nombreStage) {
            return ["idNombreStage" => $nombreStage["idNombreStage"], "periode" => $nombreStage["intitule"], "debut" => $nombreStage["dateDebut"], "fin" => $nombreStage["dateFin"], "nombre" => $nombreStage["nombre"]];
        } else {
            return [];
        }

    }

}
