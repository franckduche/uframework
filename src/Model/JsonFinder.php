<?php

namespace Model;

use Exception\StatusNotFoundException;

class JsonFinder implements FinderInterface
{
	private $statuses;
	
	public function __construct($path)
	{
		$this->statuses = file_get_contents($path);
	}
	
    public function findAll()
    {
		if (!$this->statuses) {
			throw new StatusNotFoundException("Statuses not found.");
		}
		if ($this->statuses === "[]") {
			return array();
		}
		$arrayStatuses = json_decode($this->statuses, true);
		$arrayStatusesObjects = array();
		foreach ($arrayStatuses['statuses'] as $arrayStatus) {
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
    
    public function toJson($statuses)
    {
		$arrayStatuses = array();
		foreach ($statuses as $status) {
			$arrayStatuses['statuses'][] = [
				"id" => $status->getId(),
				"content" => $status->getContent(),
				"username" => $status->getUsername()
			];
		}
		return json_encode($arrayStatuses);
	}
}
