<?php
namespace App;

use App\RemoteAPI\RemoteAPI;

class RApi {
	
	private static $store;
	public static $debug = false;

	public static function init($path,$method)
	{
		$host = (self::getConfig('host')) ? self::getConfig('host').'/'.ltrim($path,'/') : $path;
		$api = new RemoteAPI($host,self::$debug);
		$api->method($method);

		return $api;
	}

	public static function get($path='/') 
	{
		return self::init($path,'GET');
	}

	public static function post($path='/') 
	{
		return self::init($path,'POST');
	}

	public static function put($path='/') 
	{
		return self::init($path,'PUT');
	}

	public static function delete($path='/') 
	{
		return self::init($path,'DELETE');
	}

	public static function patch($path='/') 
	{
		return self::init($path,'PATCH');
	}

	public static function setConfig($key,$value='')
	{
		if(is_array($key)) {
			foreach ($key as $k => $value) {
				self::$store[$k] = $value;	
			}
		} else {
			if( $key == 'header' && !is_array($value) ) $value = [$value];
				self::$store[$key] = $value;
		}
		
	}

	public static function getConfig($key='')
	{
		if( $key )
			if( isset(self::$store[$key]) )
				return ltrim(self::$store[$key]);
			else
				return '';
		else
			return self::$store;
	}
}