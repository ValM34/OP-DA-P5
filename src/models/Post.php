<?php

class Post {

    private $id;
    private $id_post;
    private $id_user;
    private $content;
    private $created_at;
    private $updated_at;

    function getId(){
        return $this->id;
    }
    function getIdPost(){
        return $this->id_post;
    }
    function getIdUser(){
        return $this->id_user;
    }
    function getContent(){
        return $this->content;
    }
    function getCreatedAt(){
        return $this->created_at;
    }
    function getUpdatedAt(){
        return $this->updated_at;
    }

    function setId($id){
        $this->id = $id;
    }
    function setIdPost($id_post){
        $this->id_post = $id_post;
    }
    function setIdUser($id_user){
        $this->id_user = $id_user;
    }
    function setContent($content){
        $this->content = $content;
    }
    function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }
    function setUpdatedAt($updated_at){
        $this->updated_ad = $updated_at;
    }
    
}


?>