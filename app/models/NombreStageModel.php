<?php
require_once '../app/db/dao/nombreStageDao.php';
class NombreStageModel
{
    private $idPeriode;
    private $idEnseignant;
    private $nombre;

    // Setters
    public function setIdPeriode($idPeriode)
    {
        $this->idPeriode = $idPeriode;
    }

    public function setIdEnseignant($idEnseignant)
    {
        $this->idEnseignant = $idEnseignant;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    // Getters
    public function getIdPeriode()
    {
        return $this->idPeriode;
    }

    public function getIdEnseignant()
    {
        return $this->idEnseignant;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function create()
    {
        $nombreStageDao = new NombreStageDao();
        return $nombreStageDao->create($this);
    }

    // list des periodes
    function list($idUtilisateur) {
        $nombreStageDao = new NombreStageDao();
        $nombreStages = [];
        foreach ($nombreStageDao->list($idUtilisateur) as $nombreStage) {
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

    public function nombreStageParEnseignantParPeriode($idPeriode, $idEnseignant)
    {
        $nombreStageDao = new NombreStageDao();
        $nombreStage = $nombreStageDao->nombreStageParEnseignantParPeriode($idPeriode, $idEnseignant);
        if ($nombreStage) {
            return ["idNombreStage" => $nombreStage["idNombreStage"], "periode" => $nombreStage["intitule"], "debut" => $nombreStage["dateDebut"], "fin" => $nombreStage["dateFin"], "nombre" => $nombreStage["nombre"]];
        } else {
            return [];
        }

    }

}
