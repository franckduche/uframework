<?php

namespace Model;

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
		$this->connection->executeQuery("INSERT INTO Statuses VALUES (:id, :username, :content)", $params);
	}
	
	public function remove(Status $status)
	{
		$params = array( "id" => $status->getId() );
		$this->connection->executeQuery("DELETE FROM Statuses WHERE id = :id", $params);
	}
}
