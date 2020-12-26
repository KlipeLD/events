<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadbookController extends TicketProviderController
{
    private static $token='f72e02929b79c96daf9e336e0a5cdb8059e60685';
    private static $url = 'https://leadbook.ru/test-task-api/';

 	public static function shows()
    {
		$Curl = new CurlController();
		$urlFull = self::$url.'shows';
        try
        {
            $events = $Curl::exec($urlFull,self::$token);
        }
        catch(\Exception $e)
        {
            return $e;
        }
		
    	return $events;
    }
    
    public static function show($showId)
    {
    	$Curl = new CurlController();
        $urlFull = self::$url.'shows/'.$showId.'/events';
        $parts = $Curl::exec($urlFull,self::$token);
        return $parts;
    }

    public static function reserveAction($partId,$data)
    {
        $Curl = new CurlController();
        $urlFull = self::$url.'events/'.$partId.'/reserve?name=yulia&places=1';
        $answer = $Curl::execPost($data,$urlFull,self::$token);
        if(is_object($answer))
        {
            return $answer;
        }
        return $answer['reservation_id'];
    }
    public static function getPlaces($partId)
    {
        $Curl = new CurlController();
        $urlFull = self::$url.'events/'.$partId.'/places';
        $places = $Curl::exec($urlFull,self::$token);
        return $places;
    }

    public static function getNameEvent($showId)
    {
        $events = self::shows();
        foreach($events as $i => $event)
        {
            if($event['id'] == $showId)
            {
                $name = $events[$i]['name'];
            }
        } 
        return $name;
    }

    public static function getPart($showId,$partId)
    {
        $parts = self::show($showId);
        foreach($parts as $i => $part)
        {
            if($part['id'] == $partId)
            {
                $partData['id'] = $parts[$i]['id'];
                $partData['showId'] = $parts[$i]['showId'];
                $partData['date'] = $parts[$i]['date'];
                $partData['showName'] = self::getNameEvent($showId);
            }
        }

        return $partData;
    }
}
