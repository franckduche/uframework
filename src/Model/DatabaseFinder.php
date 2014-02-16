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
	
    public function findAll(array $criteria = array())
    {
		$this->statuses = $this->connection->selectQuery('SELECT * FROM Statuses' . $this->getCriteriaString($criteria), array());
	
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
    
    public function findContent($criteria)
    {
	}
    
    public function findUsername($criteria)
    {
	}
	
	private function getCriteriaString(array $criteria = array())
	{
		$query = '';
		if (count($criteria) > 0) {
			foreach ($criteria as $name => $crit) {
				if (!empty($crit)) {
					$query .= ' ' . $name . ' ' . $crit;
				}
			}
		}
		return $query;
	}
}
