<?php
require_once 'Framework/Model.php';


class Comment extends Model
{
    const MAX_LENGTH_COMMENT = 255;

    private $id;
    private $created_at;
    private $content;
    private $status;
    private $user_id;
    private $article_id;
    private $picture;

    private $errors = 0;
    private $errorsMsg = [];

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
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * @param mixed $article_id
     */
    public function setArticleId($article_id)
    {
        $this->article_id = $article_id;
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param int $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrorsMsg()
    {
        return $this->errorsMsg;
    }

    /**
     * @param array $errorsMsg
     */
    public function setErrorsMsg($errorsMsg)
    {
        $this->errorsMsg = $errorsMsg;
    }


    public function checkCommentValidate()
    {
        if (Validator::isEmpty($this->getContent())) {
            $this->errors++;
            $this->errorsMsg['content'] = "Contenu vide";
        }
        if (Validator::isToUpper($this->getContent(), self::MAX_LENGTH_COMMENT)) {
            $this->errors++;
            $this->errorsMsg['content'] = "Commentaire trop long";
        }
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getPendingComments($status = null)
    {

        $sql = 'SELECT comment.created_at, comment.content, comment.article_id, comment.user_id, user.username, article.title, comment.status, comment.id FROM comment INNER JOIN user ON comment.user_id =user.id INNER JOIN article ON  comment.article_id =article.id WHERE comment.status=:status';
        $pendingComments = $this->executeRequest($sql, array(
            'status' => $status,
        ));
        return $pendingComments->fetchAll();

    }

    public function getComment($commentId)
    {
        $sql = 'SELECT comment.id, comment.content, comment.created_at, comment.status, comment.article_id, comment.user_id FROM comment WHERE comment.id =:id';

        $articleWithComment = $this->executeRequest($sql, array(
            'id' => $commentId,
        ));
        return $articleWithComment->fetch();
    }

    public function getComments($article_id, $status = null)
    {
        $sql = 'SELECT comment.id as id, comment.created_at as date, comment.content as content, comment.user_id as user_id, comment.article_id as article_id, user.username, user.picture FROM comment INNER JOIN user ON comment.user_id = user.id WHERE comment.article_id=:id';

        if ($status !== null) {
            $sql .= ' AND comment.status=:status ORDER BY comment.created_at DESC';
            $comments = $this->executeRequest($sql, array(
                'status' => $status,
                'id' => $article_id,
            ));
            return $comments->fetchAll();
        }
        $comments = $this->executeRequest($sql, array(
            'id' => $article_id,
        ));
        return $comments->fetchAll();
    }

    /**
     * @param $commentId
     */
    public function deleteComment($commentId)
    {
        $sql = 'DELETE FROM comment WHERE id=:id';
        $deleteComment = $this->executeRequest($sql, array(
            'id' => $commentId,
        ));
    }

    public function updateComment($commentId, $status)
    {
        $sql = 'UPDATE comment SET status=:status WHERE id=:id';
        $validComment = $this->executeRequest($sql, array(
            'id' => $commentId,
            'status' => $status,
        ));
    }

    public function hydrate($comment)
    {
        $this->setContent($comment->content);
        $this->setCreatedAt($comment->created_at);
        $this->setUserId($comment->user_id);
        $this->setStatus($comment->status);
        $this->setArticleId($comment->article_id);
        $this->setPicture($comment->picture);
        $this->setId($comment->id);
    }

    public function save()
    {
        $sql = "INSERT INTO comment(content, status, created_at, user_id, article_id) VALUES(:content, :status, :created_at, :user_id, :article_id)";
        $req = $this->executeRequest($sql, array(
            'content' => $this->getContent(),
            'created_at' => $this->getCreatedAt(),
            'user_id' => $this->getUserId(),
            'status' => $this->getStatus(),
            'article_id' => $this->getArticleId(),
        ));
    }
}