<?php

class Post {

    private $id;
    private $id_post;
    private $id_user;
    private $content;
    private $created_at;
    private $updated_at;

    public function getId(){
        return $this->id;
    }
    public function getIdPost(){
        return $this->id_post;
    }
    public function getIdUser(){
        return $this->id_user;
    }
    public function getContent(){
        return $this->content;
    }
    public function getCreatedAt(){
        return $this->created_at;
    }
    public function getUpdatedAt(){
        return $this->updated_at;
    }

    public function setId($id){
        $this->id = $id;
    }
    public function setIdPost($id_post){
        $this->id_post = $id_post;
    }
    public function setIdUser($id_user){
        $this->id_user = $id_user;
    }
    public function setContent($content){
        $this->content = $content;
    }
    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }
    public function setUpdatedAt($updated_at){
        $this->updated_ad = $updated_at;
    }
    
}


?>