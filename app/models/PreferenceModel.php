<?php
require_once '../app/db/dao/preferenceDao.php';
require_once '../app/db/dao/stageDao.php';
class PreferenceModel
{
    private $stageIds;
    private $idEnseignant;
    private $dateAjout;
    private $stages;
    private $periode;
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

    public function setStages($stages)
    {
        $this->stages = $stages;
    }

    public function setPeriode($periode)
    {
        return $this->periode = $periode;
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

    public function getStages()
    {
        return $this->stages;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function stageDansLaperiode($stages, $periode)
    {
        $stageDao = new StageDao();
        $stagesIds = [];
        foreach ($stageDao->stageDansLaperiode(implode(",", $stages), $periode) as $stage) {
            array_push($stagesIds, $stage["idStage"]);
        }
        return $stagesIds;
    }

    public function create()
    {
        $stages = $this->stageDansLaperiode($this->getStageIds(), $this->getPeriode());
        $preferenceDao = new PreferenceDao();
        if (empty($stages)) {
            return null;
        }
        return $preferenceDao->create($this, $stages);
    }

    // list des periodes
    function list($idUtilisateur) {
        $preferenceDao = new PreferenceDao();
        $preferences = [];
        foreach ($preferenceDao->list($idUtilisateur) as $preferenceDao) {
            array_push($preferences, ["id" => $nombreStage["idNombreStage"], "periode" => $nombreStage["intitule"], "debut" => $nombreStage["dateDebut"], "fin" => $nombreStage["dateFin"], "nombre" => $nombreStage["nombre"]]);
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
