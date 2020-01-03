<?php
require_once "Framework/Model.php";

class Billet extends Model
{
    public function getArticles($nbStart = null, $nbEnd = null)
    {
        $sql = 'SELECT * FROM article ORDER BY ID DESC';

        if ($nbStart !== null OR $nbEnd !== null) {
            $sql .= "LIMIT " . $nbStart . ", " . $nbEnd;
        }
        $articles = $this->executeRequest($sql);
        return $articles;
    }

    /**
     * @param $idArticle
     * @return mixed
     * @throws Exception
     */
    public function getArticle($idArticle)
    {
        $sql = 'select BIL_ID as id, BIL_DATE as date,'
            . 'BIL_TITLE as titre, BIL_CONTENT as content from ARTICLE'
            . 'where BIL_ID=?';
        $article = $this->executeRequest($sql, array($idArticle));
        if ($article->rowCount() > 0)
            return $article->fetch();
        else
            throw new Exception("Aucun article ne correspond à l'identifiant '$idArticle'");
    }
}