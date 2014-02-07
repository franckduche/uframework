<?php

namespace Model;

use Exception\HttpException;

class InMemoryFinder implements FinderInterface 
{
	/**
     * @var array
     */
    private $storage = array();
    
    public function __construct()
    {
		$this->storage = [ (new Status("Mathieu", "Salut je comprends rien")),
			(new Status("Mathieu", "Salut je comprends rie")),
			(new Status("Mathieu", "Salut je comprends ri")),
			(new Status("Mathieu", "Salut je comprends r")),
			(new Status("Mathieu", "Salut je comprends")),
			(new Status("Mathieu", "Salut je comprend"))
        ];
	}

    public function findAll()
    {
        return $this->storage;
    }

    public function findOneById($id)
    {
        $result = null;
        foreach ($this->storage as $status) {
            if ($id === $status->getId()) {
                $result = $status;
            }
        }
        if (null === $result) {
			throw new StatusNotFoundException("Status not found.");
		}
        return $result;
    }
    
}
