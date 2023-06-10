<?php
require_once '../app/db/dao/preferenceDao.php';
require_once '../app/db/dao/stageDao.php';
require_once '../app/db/dao/periodeDao.php';

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
    // Recuperer les stages dans une période donnée
    public function stageDansLaperiode($stages, $periode)
    {
        $stageDao = new StageDao();
        $stagesIds = [];
        foreach ($stageDao->stageDansLaperiode(implode(",", $stages), $periode) as $stage) {
            array_push($stagesIds, $stage["idStage"]);
        }
        return $stagesIds;
    }
    // créer une preference
    public function create()
    {
        $stages = $this->stageDansLaperiode($this->getStageIds(), $this->getPeriode());

        $preferenceDao = new PreferenceDao();
        if (empty($stages)) {
            return null;
        }
        return $preferenceDao->create($this, $stages);
    }

    // modifier une preference
    public function update()
    {

        $preferenceDao = new PreferenceDao();

        $preferenceDao->enleverLesStages($this);
        return $preferenceDao->update($this);
    }

    public function list_des_preferences_lie_au_stages($idStages, $idPeriode)
    {
        $preferenceDao = new PreferenceDao();
        $preferences = [];

        foreach ($preferenceDao->list_des_preferences_lie_au_stages($idStages, $idPeriode) as $preference) {
            $preferenceItem = [];
            $preferenceItem[$preference["idStage"]] = $preference["preferenceIdEnseignant"];
            array_push($preferences, $preferenceItem);
        }
        return $preferences;
    }

    public function toutesPeriodes()
    {
        $periodeDao = new PeriodeDao();
        $periodes = [];
        foreach ($periodeDao->list() as $periode) {
            array_push($periodes, ["idPeriode" => $periode["idPeriode"], "dateDebut" => $periode["dateDebut"], "dateFin" => $periode["dateFin"], "intitule" => $periode["intitule"], "courant" => $periode["courant"]]);
        }
        return $periodes;

    }

    // list des preferences
    function list($idUtilisateur) {
        $periodes = $this->toutesPeriodes();
        $preferenceDao = new PreferenceDao();
        $preferences = [];
        foreach ($periodes as $periode) {
            $preference = $preferenceDao->preferenceParPeriode($idUtilisateur, $periode["idPeriode"]);
            if ($preference->rowCount() > 0) {
                $periode["stages"] = [];
                $periode["idPreference"] = null;
                foreach ($preference as $pref) {
                    if ($periode["idPreference"] == null) {
                        $periode["idPreference"] = $pref["idPreference"];
                    }
                    $stage = ["idStage" => $pref["idStage"], "intituleProjet" => $pref["intituleProjet"], "nomEntreprise" => $pref["nomEntreprise"], "adresse" => $pref["adresse"]];
                    array_push($periode["stages"], $stage);
                }
                array_push($preferences, $periode);
            }

        }
        return $preferences;
    }

    // trouver une preference par l'id de l'enseignant et l'id de la periode
    public function get($idUtilisateur, $idPeriode)
    {
        $preferenceDao = new PreferenceDao();
        $periode = array_filter($this->toutesPeriodes(), function ($period) use ($idPeriode) {
            return $period["idPeriode"] == $idPeriode;
        })[0];

        if ($periode) {
            $preference = $preferenceDao->preferenceParPeriode($idUtilisateur, $idPeriode);
            if ($preference->rowCount() > 0) {
                $periode["stages"] = [];
                foreach ($preference as $pref) {
                    $stage = ["idStage" => $pref["idStage"], "intituleProjet" => $pref["intituleProjet"], "nomEntreprise" => $pref["nomEntreprise"], "adresse" => $pref["adresse"]];
                    array_push($periode["stages"], $stage);
                }
            }
        } else {
            return [];
        }
        return $periode;
    }
}
