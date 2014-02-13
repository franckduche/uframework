<?php

class StatusMapperTest extends TestCase
{
	private $finder;
	
	public function setUp()
    {
        $this->finder = new Model\StatusMapper($this->getMock('MockConnection'));
    }

	public function testFindAll()
	{
		$result = $this->finder->findAll();
		$this->assertTrue(is_array($result));
	}
}