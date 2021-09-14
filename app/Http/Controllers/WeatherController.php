<?php

namespace App\Http\Controllers;

use App\Models\FollowedCity;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\City;

class WeatherController extends Controller
{
    public function index()
    {
        $id=Auth::user()->id;
        $followedCities = User::with('cities')->findOrFail($id);
        if(count($followedCities->cities)!=0)
        {
        $i=0;
        foreach($followedCities->cities as $city)
        {
            $url='https://api.openweathermap.org/data/2.5/onecall?lat='.$city->lat.'&lon='.$city->lot.'&exclude=minutely,alerts&units=metric&appid=3fb89ca03bcec852645eee47b6f9d6db';
            $contents=file_get_contents($url);
            $content=json_decode($contents);
            $weatherStatus[$i]=$content;
            $i++;
        }
        }
        else
        {
            $weatherStatus='empty';
        }
        return view('weather',compact('followedCities','weatherStatus'));   
    }
    public function delete($city_id)
    {
        $id=Auth::user()->id;
        $unfollow=FollowedCity::where('user_id',$id)->where('city_id',$city_id)->findOrFail();
        $unfollow->delete();
        return back();
    }
    public function add(Request $request)
    {
        $city=City::where('name',$request->new)->first();
            $id=Auth::user()->id;
            $follow=new FollowedCity;
            $follow->user_id=$id;
            $follow->city_id=$city->id;
            $follow->save();
        return back();
    }
}
