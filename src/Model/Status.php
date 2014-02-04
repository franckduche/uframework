<?php

// Model/Status.php

namespace Model;

class Status
{
	private $id;
	private $content;
	private $username;
	
	public function __construct($username, $content)
	{
		$this->username = htmlentities($username);
		$this->content = htmlentities($content);
		$this->id = uniqid();
	}
	
	public static function fromArray(array $statusArray = array())
	{
		return (new self($statusArray['username'], $statusArray['content']))->setId($statusArray['id']);
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	
	public function getContent()
	{
		return $this->content;
	}
	
	public function getUsername()
	{
		return $this->username;
	} 
}
