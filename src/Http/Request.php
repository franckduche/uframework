<?php

namespace Http;

use \Negotiation\FormatNegotiator;

class Request
{
	const GET    = 'GET';

    const POST   = 'POST';

    const PUT    = 'PUT';

    const DELETE = 'DELETE';
    
    private $parameters;
    
    private $negotiator;
    
    public function __construct(array $query = array(), array $request = array())
    {
		$this->parameters = array_merge($query, $request);
		$this->negotiator = new FormatNegotiator();
	}
    
    public function getMethod()
    {
		$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::GET;
		if (self::POST === $method) {
			return $this->getParameter('_method', $method);
		}
		return $method;
	}
	
	public function getUri()
	{
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		if ($pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		return $uri;
	}
	
	public static function createFromGlobals()
	{
		$request = array();
		if (isset($_SERVER['HTTP_CONTENT_TYPE']) && $_SERVER['HTTP_CONTENT_TYPE'] === "application/json") {
			$data    = file_get_contents('php://input');
			$request = @json_decode($data, true);
		}
		if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === "application/json") {
			$data    = file_get_contents('php://input');
			$request = @json_decode($data, true);
		}
		return new self($_GET, array_merge($request, $_POST));
	}
	
	public function getParameter($name, $default = null)
	{
		$result = null;
		$result = $this->parameters[$name];
		return $result;	
	}
	
	public function guessBestFormat()
	{
		$acceptHeader = $_SERVER['HTTP_ACCEPT'];
		$priorities = array("html", "application/json");
		return $this->negotiator->getBestFormat($acceptHeader, $priorities);
	}

}
