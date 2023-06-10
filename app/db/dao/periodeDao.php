<?php
require_once "../app/db/basededonnee.php";

class PeriodeDao
{

    public function create($periode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "INSERT INTO periode (dateDebut, dateFin, intitule, courant) VALUES (?,?,?,?)";
        $connexion->prepare($sql)->execute([$periode->getDateDebut(), $periode->getDateFin(), $periode->getIntitule(), $periode->getCourant()]);
        return $connexion->lastInsertId();
    }

    public function update($periode, $idPeriode)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "UPDATE periode SET dateDebut=?, dateFin=?, intitule=? WHERE idPeriode=?";

        $connexion->prepare($sql)->execute([$periode->getDateDebut(), $periode->getDateFin(), $periode->getIntitule(), $idPeriode]);

        return $connexion->lastInsertId();
    }

    function list() {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $sql = "SELECT * FROM periode";
        return $connexion->query($sql);
    }

    public function get($id)
    {
        $bd = new Basededonnee();
        $connexion = $bd->connexion();

        $stmt = $connexion->prepare("SELECT * FROM periode where idPeriode=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
