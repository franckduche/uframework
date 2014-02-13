<?php

class AppTest extends TestCase
{
	private $connection;
	
	public function setUp()
    {
        $this->connection = $this->getMock('MockConnection');
    }

	public function testSelectQuery()
	{
		$result = $this->connection->selectQuery('SELECT * FROM Statuses', array());
		$this->assertTrue(is_array($result));
	}
}