<?php
require_once "Framework/Model.php";

class Billet extends Model {
    public function getBillets($nbStart = null, $nbEnd = null){
        $sql = 'SELECT * FROM t_billet ORDER BY BIL_ID DESC';

        if ($nbStart !== null OR $nbEnd !== null){
            $sql .= "LIMIT ".$nbStart.", ".$nbEnd;
        }
        $billets = $this->executeRequest($sql);
        return $billets;
    }

    /**
     * @param $idBillet
     * @return mixed
     * @throws Exception
     */
    public function getBillet($idBillet){
        $sql = 'select BIL_ID as id, BIL_DATE as date,'
            .'BIL_TITRE as titre, BIL_CONTENT as content from T_BILLET'
            .'where BIL_ID=?';
        $billet = $this->executeRequest($sql, array($idBillet));
        if ($billet->rowCount() > 0)
            return $billet->fetch();
        else
            throw new Exception("Aucun billet ne correspond à l'identifiant '$idBillet'");
    }
}