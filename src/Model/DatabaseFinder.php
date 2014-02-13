<?php

namespace Model;

use Exception\StatusNotFoundException;

class DatabaseFinder implements FinderInterface
{
	/**
     * @var array
     */
	private $statuses;
	
	/**
     * @var Connection
     */
	private $connection;
	
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
		$this->statuses = $this->connection->selectQuery('SELECT * FROM Statuses', array());
	}
	
    public function findAll()
    {
		if (!$this->statuses) {
			throw new StatusNotFoundException("Statuses not found.");
		}
		if ($this->statuses === "[]") {
			return array();
		}
		
		$arrayStatusesObjects = array();
		foreach ($this->statuses as $arrayStatus) {
			$arrayStatusesObjects [] = Status::fromArray($arrayStatus);
		}
		return $arrayStatusesObjects;
    }

    public function findOneById($id)
    {
		$statuses = $this->findAll();
		$result = null;
        foreach ($statuses as $status) {
            if ($id === $status->getId()) {
                $result = $status;
            }
        }
        if (null === $result) {
			throw new StatusNotFoundException("Status not found.");
		}
        return $result;
    }
    
    public function findContent()
    {
	}
    
    public function findUsername()
    {
	}
}
