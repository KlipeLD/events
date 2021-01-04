<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LeadbookController;

class EventsController extends Controller
{
    private $event;

    public function __construct(LeadbookController $event)
    {
        $this->event = $event;
    }

    public function shows()
    {
    	$events = $this->event->shows();
    	return view('welcome',[
            'events' =>$events
        ]);
    }

    public function show($showId)
    {
        $partsEvent = $this->event->show($showId);
        $nameEvent = $this->event->getNameEvent($showId);
        return view('events.partsevent',[
            'partsEvent' =>$partsEvent,
            'nameEvent' => $nameEvent
        ]);
    }

    public function reserve($showId,$partId)
    {
        $partEvent = $this->event->getPart($showId,$partId);
        $places = $this->event->getPlaces($partId);
        return view('events.reserve',[
            'partEvent' =>$partEvent,
            'places' =>$places
        ]);
    }
    public function reserveAction($partId)
    {
        $this->validateReserveData();
        $error = 0;
        $data['name'] = request('name');
        $selectedPlaces = explode(",", request('places')[0]);
            
        if(is_object($this->getFreePlaces($partId,$selectedPlaces)))
        {
            $error = 1;
        }
        else
        {
            $data['places'] = $this->getFreePlaces($partId,$selectedPlaces)[1];
            $reservationData['notFreePlaces'] = $this->getFreePlaces($partId,$selectedPlaces)[0];
        }
        $reservationData['freePlaces'] = $data['places'];
        $reservationData['id'] = $this->event->reserveAction($partId,$data);
        $reservationData['name'] = $data['name'];
        return view('events.confirmbooking',[
            'reservationData' =>$reservationData,
            'error' => $error
        ]);
    }

    private function getFreePlaces($partId,$selectedPlaces)
    {
        $places = $this->event->getPlaces($partId);
        if(is_object($places))
        {
            return $places;
        }
        $reservationData[1] = [];
        $reservationData[0] = [];
        foreach($selectedPlaces as $i => $selectedPlace)
        {
            foreach ($places as $key => $place) 
            {
                if($place['id'] == $selectedPlace)
                {
                    if($place['is_available'])
                    {
                        array_push($reservationData[1],$place['id']);
                    }
                    else
                    {
                        array_push($reservationData[0],$place['id']);
                    }
                }
            }
        }
        return $reservationData;
    }

    protected function validateReserveData()
    {
        request()->validate([
            'name'=> ['required','min:1','max:255'],
            'places' => ['required']
        ]);
    }

}