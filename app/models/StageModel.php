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
    function list($idUtilisateur, $typeUtilisateur, $attribue) {
        $stageDao = new StageDao();
        $stages = [];
        $type_values = $this->typeUtilisateur($typeUtilisateur, $attribue);
        foreach ($stageDao->list($idUtilisateur, $type_values) as $stage) {
            $stag = [
                "idStage" => $stage["idPeriode"],
                "intituleProjet" => $stage["intituleProjet"],
                "nomEntreprise" => $stage["nomEntreprise"],
                "adresse" => $stage["adresse"],
            ];

            if ($attribue == true) {
                $stag["nom" . ucfirst($type_values['autreUtilisateur']) . ""] = $stage["nom"];
                $stag["prenom" . ucfirst($type_values['autreUtilisateur']) . ""] = $stage["prenom"];
                $stag["email" . ucfirst($type_values['autreUtilisateur']) . ""] = $stage["email"];
                if ($typeUtilisateur == "enseignant") {
                    $stag["numeroEtudiant"] = $stage["numeroEtudiant"];
                    $stag["numeroNational"] = $stage["numeroNational"];
                    $stag["parcours"] = $stage["parcours"];
                } else {
                    $stag["titre"] = $stage["titre"];
                    $stag["specialisation"] = $stage["specialisation"];
                }
            }
            array_push(
                $stages,
                $stag
            );
        }
        return $stages;
    }

    public function list_sujet_disponible($typeUtilisateur)
    {
        $stageDao = new StageDao();
        $stages = [];
        $type_values = $this->typeUtilisateur($typeUtilisateur);
        foreach ($stageDao->list_sujet_disponible($type_values) as $stage) {
            $stag = [
                "idStage" => $stage["idPeriode"],
                "intituleProjet" => $stage["intituleProjet"],
                "nomEntreprise" => $stage["nomEntreprise"],
                "adresse" => $stage["adresse"],
            ];
            array_push(
                $stages,
                $stag
            );
        }
        return $stages;
    }

    public function auto_attribue($params, $typeUtilisateur)
    {
        $stageDao = new StageDao();
        $type_values = $this->typeUtilisateur($typeUtilisateur);
        return $stageDao->auto_attribue($params, $type_values);
    }

    private function typeUtilisateur($typeUtilisateur, $attribue = false)
    {
        return [
            "utilisateur" => $typeUtilisateur,
            "autreUtilisateur" => ($typeUtilisateur == "enseignant" ? "etudiant" : "enseignant"),
            "attribue" => ($attribue == true ? "IS NOT NULL" : "IS NULL"),
        ];
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
