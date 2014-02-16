<?php

class ResponseTest extends TestCase
{
	private $response;
	
	public function setUp()
    {
        $this->response = new Http\Response('content');
    }
	
    public function testGetStatusCode()
    {
        $this->assertTrue(!empty($this->response->getStatusCode()));
		$this->assertEquals(200, $this->response->getStatusCode())
    }

    public function testGetContent()
    {
        $this->assertTrue(!empty($this->response->getContent()));
		$this->assertEquals('content', $this->response->getContent())
    }
	
	public function testSendHeaders()
	{
		$this->assertEquals(200, http_response_code());
	}
}