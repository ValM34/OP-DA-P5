<?php

namespace Models;

class Comment
{
	private ?array $comment = null;

	public function getComment()
	{
		return $this->comment;
	}

	public function setComment(?array $array): self
	{
		$this->comment = $array;

		return $this;
	}
}