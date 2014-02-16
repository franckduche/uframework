<?php

// Model/Status.php

namespace Model;

class Status
{
	/**
     * @var string
     */
	private $id;
	
	/**
     * @var string
     */
	private $content;
	
	/**
     * @var string
     */
	private $username;
	
	public function __construct($username, $content, $id = null)
	{
		$this->username = htmlentities($username);
		$this->content = htmlentities($content);
		$this->id = $id == null ? uniqid() : $id;
	}
	
	public static function fromArray(array $statusArray = array())
	{
		return new self($statusArray['username'], $statusArray['content'], $statusArray['id']);
	}
	
	public function getId()
	{
		return $this->id;
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
