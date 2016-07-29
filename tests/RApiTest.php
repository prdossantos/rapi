<?php
use PHPUnit_Framework_TestCase as TestCase;
use App\RApi;
class RApiTest extends TestCase {

	public function setUp()
	{
		RApi::setConfig('host','https://en.wikipedia.org/api/rest_v1');
		
	}

	public function testGet()
	{	
		$res = RApi::get('/page/title/Google')->run();

		$this->assertnOTNull($res->response);
	}


}