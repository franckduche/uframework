<?php

class StatusDataMapperTest extends TestCase
{
    private $con;

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
    }

    public function testPersist()
    {
        $mapper = new \Model\StatusDataMapper($this->con);

        // Example on how to count rows in the table
        // $rows = $this->con->query('SELECT COUNT(*) FROM statuses')->fetch(\PDO::FETCH_NUM);
        // $this->assertCount(0, $rows[0]);
		
		$mapper->persist(new \Model\Status('test', 'test'));
    }
	
	public function testRemove()
	{
		$mapper = new \Model\StatusDataMapper($this->con);
		$status = new \Model\Status('test', 'test');
		$mapper->persist($status);
		$mapper->remove($status);
	}
}