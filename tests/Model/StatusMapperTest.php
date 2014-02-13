<?php

class StatusMapperTest extends TestCase
{
	private $mapper;
	
	public function setUp()
    {
        $this->mapper = new Model\StatusMapper($this->getMock('MockConnection'));
    }

	public function testPersist()
	{
		$this->mapper->persist($this->getMock('Model\Status'));
	}

	public function testRemove()
	{
		$this->mapper->remove($this->getMock('Model\Status'));
	}
}