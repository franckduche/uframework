<?php

class RequestTest extends TestCase
{
	private $request;
	
	public function setUp()
    {
        $this->request = new Http\Request();
    }
	
    public function testGetMethod()
    {
        $this->assertEquals('GET', $this->request->getMethod());
	}
	
    public function testGetUri()
    {
        $this->assertEquals('/', $this->request->getUri());
	}
	
	public function testGetParameter()
	{
		$this->assertEquals(null, $this->request->getParameter('toto'));
	}
}