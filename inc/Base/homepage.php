<?php
namespace Inc\Base;


class homepage extends DB{

    public function getinfoHomePage(){
        $data = $this->pdo->query("SELECT name_banniere, content_banniere, img_banniere FROM homepage")->fetchObject();
        return $data;

    }

    public function postinfoHomePage($name_banniere, $content_banniere){
        $req = $this->pdo->prepare("UPDATE homepage SET name_banniere = ?,  content_banniere = ? WHERE id  = 1")->execute(array($name_banniere, $content_banniere));
    }


    public function postimgHomePage($img_banniere){
        $req = $this->pdo->prepare("UPDATE homepage SET img_banniere = ? WHERE id  = 1")->execute(array($img_banniere));
    }




    /** On ajoute un article au blog  */
    public function addPostBlog($namePost, $contentPost, $autheurPost, $publicPost, $img_posts){
        $req = $this->pdo->prepare("INSERT INTO post (name_posts, content_posts, date_posts, author_posts, public_posts, img_posts) VALUES (?,?,NOW(), ?, ?, ?)")->execute(array($namePost, $contentPost, $autheurPost, $publicPost, $img_posts));
    }


    /** On recupère les artcicles du BLOG   */
    public function getPostBlog(){
        $data = $this->pdo->query("SELECT * FROM post ORDER BY id DESC")->fetchAll();
        return $data;
    }

    /** On recupère les artcicles du BLOG qui sont Puiblic   */
    public function getPostBlogPublic(){
        $data = $this->pdo->query("SELECT * FROM post WHERE public_posts = 1 ORDER BY id DESC ")->fetchAll();
        return $data;
    }


    /** On recupère un Article du BLOG   */
    public function getOnePostBlog($id){
        $data = $this->pdo->query("SELECT * FROM post WHERE id = $id")->fetchObject();
        return $data;
    }

    /** Supprimer un artcicle du BLOG   */
    public function deletePostBlog($id){
        $this->pdo->prepare("DELETE FROM post WHERE id = ? LIMIT 1")->execute([$id]);
    }


    /** Modifier un artcicle du BLOG   */
    public function updatePostBlog($namePost, $contentPost, $sessionAdmin, $publicPost, $img_posts, $id){
        $req = $this->pdo->prepare("UPDATE post SET  name_posts = ?, content_posts = ?, author_posts = ?, public_posts = ?, img_posts = ?  WHERE id  = ?");
        $req->execute([$namePost, $contentPost, $sessionAdmin, $publicPost, $img_posts, $id]);
    }


    /** vérifier si un article est Public ou Non */
    public function publicPosts($postStatus)
    {
        switch ($postStatus) {
            case '0' :
                $postStatus = 'Non';
                break;
            case '1' :
                $postStatus = 'Yes';
                break;
        }
        return $postStatus;
    }
}

