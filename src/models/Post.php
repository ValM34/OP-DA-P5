<?php

namespace Models;

class Post
{
	private ?array $post = null;

	public function getPost()
	{
		return $this->post;
	}

	public function setPost(?array $array): self
	{
		$this->post = $array;

		return $this;
	}
}