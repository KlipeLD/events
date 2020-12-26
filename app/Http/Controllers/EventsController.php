<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LeadbookController;

class EventsController extends Controller
{
    public function shows()
    {
        $TicketProvider = $this->choosingProvider();
    	$events = $TicketProvider::shows();
    	return view('welcome',[
            'events' =>$events
        ]);
    }

    public function show($showId)
    {
        $TicketProvider = $this->choosingProvider();
        $partsEvent = $TicketProvider::show($showId);
        $nameEvent = $TicketProvider::getNameEvent($showId);
        return view('events.partsevent',[
            'partsEvent' =>$partsEvent,
            'nameEvent' => $nameEvent
        ]);
    }

    public function reserve($showId,$partId)
    {
        $TicketProvider = $this->choosingProvider();
        $partEvent = $TicketProvider::getPart($showId,$partId);
        $places = $TicketProvider::getPlaces($partId);
        return view('events.reserve',[
            'partEvent' =>$partEvent,
            'places' =>$places
        ]);
    }
    public function reserveAction($partId)
    {
        $this->validateReserveData();
        $error = 0;
        $TicketProvider = $this->choosingProvider();
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
        $reservationData['id'] = $TicketProvider::reserveAction($partId,$data);
        $reservationData['name'] = $data['name'];
        return view('events.confirmbooking',[
            'reservationData' =>$reservationData,
            'error' => $error
        ]);
    }

    private function getFreePlaces($partId,$selectedPlaces)
    {
        $TicketProvider = $this->choosingProvider();
        $places = $TicketProvider::getPlaces($partId);
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

    private function choosingProvider()
    {
        $TicketProvider = new LeadbookController();
        return $TicketProvider;
    }
}
