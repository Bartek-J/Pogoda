<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    public function index()
    {
        $id=Auth::user()->id;
        $followedCities = User::with('cities')->findOrFail($id);
        //dd($followedCities);
        $i=0;
        foreach($followedCities->cities as $city)
        {
            $url='https://api.openweathermap.org/data/2.5/onecall?lat='.$city->lat.'&lon='.$city->lot.'&exclude=minutely,alerts&units=metric&appid=3fb89ca03bcec852645eee47b6f9d6db';
            $contents=file_get_contents($url);
            $content=json_decode($contents);
            $weatherStatus[$i]=$content;
            $i++;
        }
        //dd($weatherStatus);
        return view('weather',compact('followedCities','weatherStatus'));
        
        
    }
}
