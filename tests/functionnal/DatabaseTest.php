<?php

class DatabaseTest extends TestCase
{
	private $finder;

    public function setUp()
    {
        $this->con = new \Model\Connection('sqlite::memory:');
        $this->con->exec(<<<SQL
CREATE TABLE IF NOT EXISTS Statuses
(
	id VARCHAR(20),
	username VARCHAR(40),
	content VARCHAR(140)
);

SQL
        );
		
        $mapper = new \Model\StatusDataMapper($this->con);
		$mapper->persist(new Status('Franck', 'salut', 'aaa'));
		$mapper->persist(new Status('Mathieu', 'iopiop'));
		
		$this->finder = new \Model\DatabaseFinder($this->con);
    }

    public function testFindAll()
    {
		$statuses = $this->finder->findAll();
		$this->assertEquals(2, count($statuses));
    }

    public function testFindOneById()
    {
		$status = $this->finder->findOneById('aaa');
		$this->assertEquals('Franck', $status->getUsername());
		$this->assertEquals('salut', $status->getContent());
    }
}
