<?php
require_once '../app/db/dao/periodeDao.php';
class PeriodeModel
{
    private $idPeriode;
    private $dateDebut;
    private $dateFin;
    private $intitule;
    private $courant;

    // Setters
    public function setIdPeriode($idPeriode)
    {
        $this->idPeriode = $idPeriode;
    }

    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;
    }

    public function setCourant($courant)
    {
        $this->courant = $courant;
    }

    // Getters
    public function getIdPeriode()
    {
        return $this->IdPeriode;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function getIntitule()
    {
        return $this->intitule;
    }

    public function getCourant()
    {
        return $this->courant;
    }

    public function create()
    {
        $periodeDao = new PeriodeDao();
        return $periodeDao->create($this);
    }

    // list des periodes
    function list() {
        $periodeDao = new PeriodeDao();
        $periodes = [];
        foreach ($periodeDao->list() as $periode) {
            array_push($periodes, ["idPeriode" => $periode["idPeriode"], "dateDebut" => $periode["dateDebut"], "dateFin" => $periode["dateFin"], "intitule" => $periode["intitule"], "courant" => $periode["courant"]]);
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
