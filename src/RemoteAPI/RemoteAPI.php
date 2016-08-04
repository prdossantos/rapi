<?php

namespace App\RemoteAPI;

/**
 * Conexão remova via cURL
 * @version 1.0.0
 */
class RemoteAPI {

    public $host;
    public $debug;
    private $options;
    
    public function __construct($host, $debug = false) {
        if(!function_exists('curl_version'))
            die('cURL is disable or not installed');
        
        $this->host = $host;
        $this->debug = $debug;
    }

    public function fields($fields='',$formData=null)
    {
        $this->options['formData'] = ( is_bool($formData) ) ? $formData : ($this->options['method'] != 'GET' ? true : false );

        if( is_null($formData) )
            if( $this->options['method'] != 'GET') $this->options['formData'] = true;
            else $this->options['formData'] = false;

        if( $fields )
            $this->options['fields'] = $fields;

        return $this;
    }

    public function header($header='')
    {
        if( $header )
            $this->options['header'] = $header;

        return $this;
    }

    public function auth($auth='')
    {
        if( $auth )
            $this->options['auth'] = $auth;
        
        return $this;
    }

    public function cookie($cookie='')
    {
        if( $cookie )
            $this->options['cookie'] = $cookie;
        
        return $this;
    }

    public function method($method='GET')
    {
        $this->options['method'] = $method;
    }

    public function path($path='/')
    {
        $this->options['path'] = $path;
    }

    public function run() {

        $this->curl(( isset($this->options['path']) ? $this->host.$this->options['path'] : $this->host ), ( isset($this->options['method']) ? $this->options['method'] : 'GET') );

        if($this->debug){
            print_r($this);
        }
        return $this;
    }

    public function curl($url='',$method='') {

        if( isset($this->options['formData']) && !$this->options['formData'])
            $url = $url.'?'.$this->buildQuery($this->options['fields']);

        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);

        if ( isset($this->options['header']) )
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->options['header']);

        if ( isset($this->options['fields']) && $this->options['formData'])
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->options['fields']);

        if ( isset($this->options['auth']) )
            curl_setopt($curl, CURLOPT_USERPWD, $this->options['auth']);
        
        if ( isset($this->options['cookie']) )
            curl_setopt($curl, CURLOPT_COOKIE, substr($this->options['cookie'],strpos($this->options['cookie'], 'SESS')));

        if( isset($this->options['ssl']) ){
            curl_setopt($curl, CURLOPT_CAINFO, $this->options['ssl']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        } else{
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        }

        $response = curl_exec($curl);
        
        $this->info = curl_getinfo($curl);

        $this->header = $this->parseHeader(explode("\r\n", $response));
            
        $http_message = (curl_error($curl)) ? curl_error($curl) : null;

        if( isset($this->header['Status']) )
            $http_message = $this->header['Status'];
        
        $this->info['http_message'] = ($http_message) ? $http_message : 'ok';

        if( isset($this->header['Data']) )
            $this->response = ($this->info['content_type'] == 'application/json') ? json_decode($this->header['Data'],true) : $this->header['Data'];
        else
            $this->response = '';
        
        return $this->response;
    }

    protected function parseHeader($headerArr=array())
    {
        if( is_array($headerArr) && $headerArr ) {
            $header = array();
            foreach ($headerArr as $value) {
                $value = trim($value);
                if(substr($value,0,5) == 'HTTP/') {
                    $header['Status'] = trim(str_replace(substr($value,0,8),'',$value));
                } else if(substr($value,0,1) == '{' || substr($value,0,1) == '[' || substr($value,0,1) == '<html' || substr($value,0,1) == '<div' || substr($value,0,1) == '<title' || (substr($value,0,1) == '<' &&  strpos($value, '>') !== false)) {
                    $header['Data'] = $value;
                } else if(strpos($value, ':')) {
                    $header[substr($value,0,strpos($value, ':'))] = trim(substr($value,strpos($value, ':'),strlen($value)));
                } else {
                    $header['Data'] = $value;
                }
            }

            return $header; 
        }

        return null;
    }

    protected function buildQuery($fields)
    {
        if( $fields && is_array($fields) ) {
            $query='';
            foreach ($fields as $key => $value) {
                $query .= $key.'='.$value;
            }
            return $query;
        } else if(is_string($fields) ){
            return $fields;
        } else {
            return '';
        }
    }
}
