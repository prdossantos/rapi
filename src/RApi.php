<?php
namespace App;

use App\RemoteAPI\RemoteAPI;

class RApi {
	
	private static $store;
	public static $debug = false;

	public static function init($path,$method,$fields=array(),$header=array())
    {
        $host = (self::getConfig('host')) ? self::getConfig('host').'/'.ltrim($path,'/') : $path;
        $api = new RemoteAPI($host,self::$debug);
        $api->method($method);
        if($fields || $header){
            $api->fields($fields,( $method != 'GET' ? true : false));
            $api->header($header);
            return $api->run();
        }

        return $api;
    }

    public static function get($path='/',$fields=array(),$header=array()) 
    {
        return self::init($path,'GET',$fields,$header);
    }

    public static function post($path='/',$fields=array(),$header=array()) 
    {
        return self::init($path,'POST',$fields,$header);
    }

    public static function put($path='/',$fields=array(),$header=array()) 
    {
        return self::init($path,'PUT',$fields,$header);
    }

    public static function delete($path='/',$fields=array(),$header=array())
    {
        return self::init($path,'DELETE',$fields,$header);
    }

    public static function patch($path='/',$fields=array(),$header=array())
    {
        return self::init($path,'PATCH',$fields,$header);
    }

	public static function setConfig($key,$value='')
	{
		if(is_array($key)) {
			foreach ($key as $k => $value) {
				self::$store[$k] = $value;	
			}
		} else {
			if( $key == 'header' && !is_array($value) ) 
                $value = [$value];
				
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