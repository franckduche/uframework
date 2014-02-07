<?php

class StatusMapper
{
	private $connection;
	
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}
	
	public function persist(Status $status)
	{
		$params = array(
			"id" => $status->getId(),
			"username" => $status->getUsername(),
			"content" => $status->getContent()
		);
		$this->connection->executeQuery("INSERT INTO Status VALUES (:id, :username, :content)", $params);
	}
	
	public function remove(Status $status)
	{
		$params = array( "id" => $status->getId() );
		$this->connection->executeQuery("DELETE FROM Status WHERE id = :id", $params);
	}
}