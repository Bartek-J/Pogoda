@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <h2>Weather Forecast</h2>
    </div>
<ul class="nav nav-tabs">
    @foreach($followedCities->cities as $city)
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#m{{$loop->iteration}}">{{$city->name}}</a></li>
    @endforeach
</ul>
<div class="tab-content p-3" id="weathermain">
@foreach($followedCities->cities as $city)
    <div id="m{{$loop->iteration}}" class="tab-pane fade in @if($loop->first) active show @endif">

        <h3>{{$city->name}}, {{$city->country}}</h3>
        <p>    
            @foreach($weatherStatus[$loop->index]->current->weather as $weather)
            <img src="{{asset('storage/icons/'. $weather->icon .'.png')}}">
            {{$weatherStatus[$loop->index]->current->temp}} °C  <br>
            Feels like {{$weatherStatus[$loop->index]->current->feels_like}} °C 
            {{$weather->description}} <br>
            Humidity: {{$weatherStatus[$loop->index]->current->humidity}}%
            Wind speed: {{$weatherStatus[$loop->index]->current->wind_speed}}m/s<br>
            Pressure: {{$weatherStatus[$loop->index]->current->pressure}}
            Dew point: {{$weatherStatus[$loop->index]->current->dew_point}}<br>
            Sunrise: {{gmdate("g:ia", $weatherStatus[$loop->index]->current->sunrise);}}
            Sunset: {{gmdate("g:ia", $weatherStatus[$loop->index]->current->sunset);}}
            @endforeach
        </p>
        <h5>8-day forecast</h5>
        <p>
            @foreach($weatherStatus[$loop->index]->daily as $daily)
            <p>
                {{gmdate("D, M d", $daily->dt);}}
                <img src="{{asset('storage/icons/'. $daily->weather[0]->icon .'.png')}}" height="40px">
                {{$daily->temp->max }} /{{$daily->temp->min }} °C 
                {{$daily->weather[0]->description}}
            </p>
            @endforeach
        </p>
    </div>
@endforeach    
</div>
</div>





@endsection