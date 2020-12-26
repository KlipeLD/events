<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlController extends Controller
{
    public static function exec($url,$token)
    {
    	$curl = curl_init(); 
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: token='. $token]);   
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    	if( ! $resultEncode = curl_exec($curl))
	    {
	        trigger_error(curl_error($curl));
	    }
    	curl_close($curl);
    	$result = json_decode($resultEncode,true);
        try
        {
            if (!array_key_exists('response',$result))
            {
                throw new \Exception('Error from server.');
            }   
        }
        catch(\Exception $e)
        {
            return $e;
        }    
        
        return $result['response'];
    }

    public static function execPost($data,$url,$token)
    {
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: token='. $token]);   
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        if( ! $resultEncode = curl_exec($curl))
        {
            trigger_error(curl_error($curl));
        }
        curl_close($curl);
        $result = json_decode($resultEncode,true);
        try
        {
            if (!array_key_exists('response',$result))
            {
                throw new \Exception('Error from server.');
            }   
        }
        catch(\Exception $e)
        {
            return $e;
        }  
        return $result['response'];
    }
}
