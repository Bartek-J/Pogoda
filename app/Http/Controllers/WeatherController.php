<?php

namespace App\Http\Controllers;

use App\Models\FollowedCity;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Http\Requests\FollowRequest;

class WeatherController extends Controller
{
    public function index()
    {
        $id=Auth::user()->id;
        $followedCities = User::with('cities')->findOrFail($id);
        $followedAmount=FollowedCity::where('user_id',$id)->count();
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
        return view('weather',compact('followedCities','weatherStatus','followedAmount'));   
    }
    public function delete($city_id)
    {
        $id=Auth::user()->id;
        $unfollow=FollowedCity::where('user_id',$id)->where('city_id',$city_id)->first();
        $unfollow->delete();
        return back();
    }
    public function add(FollowRequest $request)
    {
            $city=City::where('name',$request->new)->first();
            if($city == NULL)
            {
                return back()->with([
                    'status' => [
                        'type' => 'danger',
                        'content' => "Couldn't find matching city in our database!"
                    ]
                ]);
            }
            $id=Auth::user()->id;
            $isFollowingAlready=FollowedCity::where('user_id',$id)->where('city_id',$city->id)->count();
            if($isFollowingAlready!=0)
            {
                return back()->with([
                    'status' => [
                        'type' => 'danger',
                        'content' => 'You already follow that city!'
                    ]
                ]);
            }
            $followedAmount=FollowedCity::where('user_id',$id)->count();
            if($followedAmount>=5)
            {
                return back()->with([
                    'status' => [
                        'type' => 'danger',
                        'content' => 'You cannot follow more than 5 cities at once!'
                    ]
                ]);
            }
            {
            $follow=new FollowedCity;
            $follow->user_id=$id;
            $follow->city_id=$city->id;
            $follow->save();
            }
        return back();
    }
}
