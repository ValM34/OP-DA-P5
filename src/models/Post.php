<?php

namespace Models;

class Post
{
  private $id;
  private $id_post;
  private $id_user;
  private $content;
  private $created_at;
  private $updated_at;

  public function getId()
  {
    return $this->id;
  }

  public function getIdPost()
  {
    return $this->id_post;
  }

  public function getIdUser()
  {
    return $this->id_user;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  public function getUpdatedAt()
  {
    return $this->updated_at;
  }

  public function getPost()
  {
    return $this->post;
  }

  public function setId(?int $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function setIdPost(?int $id_post): self
  {
    $this->id_post = $id_post;

    return $this;
  }

  public function setIdUser(?int $id_user): self
  {
    $this->id_user = $id_user;

    return $this;
  }

  public function setContent(?string $content): self
  {
    $this->content = $content;

    return $this;
  }

  public function setCreatedAt(?string $created_at): self
  {
    $this->created_at = $created_at;

    return $this;
  }

  public function setUpdatedAt(?string $updated_at): self
  {
    $this->updated_at = $updated_at;

    return $this;
  }
}
