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

    public function update($idNombreStage)
    {
        $nombreStageDao = new NombreStageDao();
        return $nombreStageDao->update($this, $idNombreStage);
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
            return ["idNombreStage" => $nombreStage["idNombreStage"], "idPeriode" => $nombreStage["idPeriode"], "periode" => $nombreStage["intitule"], "debut" => $nombreStage["dateDebut"], "fin" => $nombreStage["dateFin"], "nombre" => $nombreStage["nombre"]];
        } else {
            return [];
        }

    }

    public function nombreLimitStageParEnseignantParPeriode($idPeriode, $idEnseignants)
    {
        $nombreStageDao = new NombreStageDao();
        $nombreLimites = [];
        foreach ($nombreStageDao->nombreLimitStageParEnseignantParPeriode($idPeriode, implode(",", $idEnseignants)) as $nombre) {

            $nombreLimites[$nombre["enseignantId"]] = $nombre["nombre"];
            // array_push($nombreLimites, $nombreLimite);
        }
        return $nombreLimites;
    }

}
