<?php
require_once "Framework/Model.php";

class Article extends Model
{
    const MAX_LENGTH_TITLE = 60;
    const MAX_LENGTH = 255;

    private $id;
    private $created_at;
    private $content;
    private $title;
    private $picture_url;
    private $publish;
    private $user_id;
    private $alternative_text;
    private $excerpt;

    private $errors = 0;
    private $errorsMsg = [];


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture_url;
    }

    /**
     * @param mixed $picture_url
     */
    public function setPicture($picture_url)
    {
        $this->picture_url = $picture_url;
    }

    /**
     * @return mixed
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * @param mixed $publish
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getAlternativeText()
    {
        return $this->alternative_text;
    }

    /**
     * @param mixed $alternative_text
     */
    public function setAlternativeText($alternative_text)
    {
        $this->alternative_text = $alternative_text;
    }

    /**
     * @return mixed
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @param mixed $excerpt
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getErrorsMsg()
    {
        return $this->errorsMsg;
    }

    public function getAllArticles($publish = null, $nbStart = null, $nbEnd = null)
    {
        $sql = 'SELECT * FROM article ';

        if ($publish != null) {
            $sql .= 'WHERE publish =:publish ORDER BY ID DESC ';
            $req = $this->executeRequest($sql, array(
                'publish' => $publish,
            ));
            return $req->fetchAll();
        } else {
            $sql .= 'ORDER BY ID DESC';
        }

        if ($nbStart !== null or $nbEnd !== null) {
            $sql .= "LIMIT " . $nbStart . ", " . $nbEnd;
        }
        $req = $this->executeRequest($sql);
        return $req->fetchAll();
    }

    /**
     * @param $articleId
     * @return mixed
     * @throws Exception
     */
    public function getOneArticle($articleId)
    {
        $sql = 'SELECT id as id, created_at as created_at, content as content, title as title, excerpt as excerpt, user_id as user_id, picture_url as picture_url from article WHERE id=:id';
        $article = $this->executeRequest($sql, array(
            'id' => $articleId,
        ));
        if ($article->rowCount() == 1)
            return $article->fetch();

        else
            throw new Exception("Aucun article ne correspond à l'identifiant '$articleId'");
    }

    public function updatePicture()
    {
        $sql = 'UPDATE article SET picture_url=:picture WHERE id=:id';
        $updatePicture = $this->executeRequest($sql, array(
            'id' => $this->getId(),
            'picture' => $this->getPicture()
        ));
    }

    public function savePictureArticle()
    {
        $sql = "INSERT INTO article(picture_url) VALUES(:picture)";
        $req = $this->executeRequest($sql, array(
            'picture' => $this->getPicture(),

        ));

    }

    public function updateArticle()
    {
        $sql = 'UPDATE article SET title=:title, content=:content, picture_url=:picture_url, publish=:publish, created_at=:created_at, excerpt=:excerpt, alternative_text=:alternative_text, user_id=:user_id WHERE id=:id';
        $updateArticle = $this->executeRequest($sql, array(
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'picture_url' => $this->getPicture(),
            'publish' => $this->getPublish(),
            'created_at' => $this->getCreatedAt(),
            'excerpt' => $this->getExcerpt(),
            'alternative_text' => $this->getAlternativeText(),
            'user_id' => $this->getUserId(),
        ));
    }

    /**
     * @param $articleId
     */
    public function deleteArticle($articleId)
    {

        $sql = 'DELETE FROM article WHERE id =:id';
        $deleteArticle = $this->executeRequest($sql, array(
            'id' => $this->getId(),
        ));
    }

    private function checkArticleTitle()
    {
        if (Validator::isEmpty($this->getTitle())) {
            $this->errors++;
            $this->errorsMsg['title'] = "Insérer un titre";
        }

        if (Validator::isToUpper($this->getTitle(), self::MAX_LENGTH_TITLE)) {
            $this->errors++;
            $this->errorsMsg['title'] = "Titre trop long";
        }
        if ($this->checkTitleInBdd() >= 1) {
            $this->errors++;
            $this->errorsMsg['title'] = "Le titre de l'article existe déjà";
        }
    }

    private function checkArticleTitleUpdate()
    {
        if (Validator::isEmpty($this->getTitle())) {
            $this->errors++;
            $this->errorsMsg['title'] = "Insérer un titre";
        }

        if (Validator::isToUpper($this->getTitle(), self::MAX_LENGTH_TITLE)) {
            $this->errors++;
            $this->errorsMsg['title'] = "Titre trop long";
        }
    }

    private function checkArticleContent()
    {
        if (Validator::isEmpty($this->getContent())) {
            $this->errors++;
            $this->errorsMsg['content'] = "Insérer du contenu";
        }

    }

    private function checkArticleExcerpt()
    {
        if (Validator::isEmpty($this->getExcerpt())) {
            $this->errors++;
            $this->errorsMsg['excerpt'] = "Insérer du contenu";
        }
        if (Validator::isToUpper($this->getExcerpt(), self::MAX_LENGTH)) {
            $this->errors++;
            $this->errorsMsg['excerpt'] = "Contenu de l'extrait trop long";
        }
    }

    private function checkPictureUrl()
    {
        if (Validator::isToUpper($this->getPicture(), self::MAX_LENGTH)) {
            $this->errors++;
            $this->errorsMsg['picture_url'] = "Titre de l'image trop long";
        }
    }

    public function pictureUpload()
    {

    }

    public function formArticleUpdateValidate()
    {
        $this->checkArticleTitleUpdate();
        $this->checkArticleContent();
        $this->checkPictureUrl();
        $this->checkArticleExcerpt();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formArticleValidate()
    {
        $this->checkArticleTitle();
        $this->checkArticleContent();
        $this->checkPictureUrl();
        $this->checkArticleExcerpt();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function articleValidate()
    {
        if ($this->checkTitleInBdd() === 0 && $this->checkContentInBdd() === 0) {
            return true;
        }
        return false;
    }

    public function checkTitleInBdd()
    {
        $sql = 'SELECT title FROM article WHERE title=:title';
        $req = $this->executeRequest($sql, array('title' => $this->getTitle()));
        return $req->rowCount();
    }

    public function checkContentInBdd()
    {
        $sql = 'SELECT content FROM article WHERE content=:content';
        $req = $this->executeRequest($sql, array('content' => $this->getContent()));
        return $req->rowCount();
    }

    public function hydrate($article)
    {
        $this->setContent($article->content);
        $this->setCreatedAt($article->created_at);
        $this->setUserId($article->user_id);
        $this->setTitle($article->title);
        $this->setPicture($article->picture_url);
        $this->setPublish($article->publish);
        $this->setExcerpt($article->excerpt);
        $this->setAlternativeText($article->alternative_text);
        $this->setId($article->id);
    }

    public function save()
    {
        $sql = "INSERT INTO article(title, content, picture_url, publish, created_at, excerpt, alternative_text, user_id) VALUES(:title, :content, :picture_url, :publish, :created_at, :excerpt, :alternative_text, :user_id)";
        $req = $this->executeRequest($sql, array(
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'picture_url' => $this->getPicture(),
            'publish' => $this->getPublish(),
            'created_at' => $this->getCreatedAt(),
            'excerpt' => $this->getExcerpt(),
            'alternative_text' => $this->getAlternativeText(),
            'user_id' => $this->getUserId(),
        ));
    }
}