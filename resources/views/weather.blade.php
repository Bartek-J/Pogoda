@extends('layouts.app')

@section('content')
@if (isset(session('status')['type']) )
<div class="container" id="status">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-{{ session('status')['type']}}">
                <button id="btnstatus" class="" onclick="document.getElementById('status').style.display = 'none';">X</button>
                {{ session('status')['content'] }}
            </div>
        </div>
    </div>
</div>
@endif
<div class="container-lg" id="maincont">
    <div id="allcontent">
        <div class="row">
            <div class="col-10 pl-4">
                <h1>Weather Forecast</h1>
            </div>
            <div class="nav-item dropdown col-2">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        @if($weatherStatus != 'empty')
        <ul class="nav nav-tabs">
            @foreach($followedCities->cities as $city)
            <li class="nav-item"><a class="nav-link @if($loop->first) active @endif" data-toggle="tab" href="#m{{$loop->iteration}}">{{$city->name}}</a></li>
            @endforeach
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#mnew">+</a></li>
        </ul>
        <div class="tab-content p-3" id="weathermain">
            @foreach($followedCities->cities as $city)
            <div id="m{{$loop->iteration}}" class="tab-pane fade in @if($loop->first) active show @endif">
                <div class="row pl-4">
                    <h2>{{$city->name}}, {{$city->country}}</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="custom_p">
                            <img src="{{asset('storage/icons/'. $weatherStatus[$loop->index]->current->weather[0]->icon .'.png')}}">
                            {{$weatherStatus[$loop->index]->current->temp}} °C
                        </p>
                        <ul class='custom_ul'>
                            <li>Feels like {{$weatherStatus[$loop->index]->current->feels_like}} °C
                                {{$weatherStatus[$loop->index]->current->weather[0]->description}}
                            </li>
                            <li> Humidity: {{$weatherStatus[$loop->index]->current->humidity}}%</li>
                            <li> Wind speed: {{$weatherStatus[$loop->index]->current->wind_speed}}m/s</li>
                            <li> Pressure: {{$weatherStatus[$loop->index]->current->pressure}}hPa </li>
                            <li> Dew point: {{$weatherStatus[$loop->index]->current->dew_point}} °C</li>
                            <li>Sunrise: {{gmdate("g:ia", $weatherStatus[$loop->index]->current->sunrise);}}</li>
                            <li>Sunset: {{gmdate("g:ia", $weatherStatus[$loop->index]->current->sunset);}}</li>
                            <li> <a href='https://openweathermap.org/city/{{$city->id}}' class="custom_a" target="_blank">More informations </a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 day8">
                        <h5>8-day forecast</h5>
                        <table>
                            @foreach($weatherStatus[$loop->index]->daily as $daily)
                            <tr>
                                <td>{{gmdate("D, M d", $daily->dt);}}</td>
                                <td><img src="{{asset('storage/icons/'. $daily->weather[0]->icon .'.png')}}" height="40px">
                                    {{$daily->temp->max }} / {{$daily->temp->min }} °C
                                </td>
                                <td class="pl-2 clouds">{{$daily->weather[0]->description}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                   
                    <form class='col-lg-5 col-md-5' action="{{route('unfollow', $city->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Unfollow</button>
                    </form>
                </div>
            </div>
            @endforeach
            <div id="mnew" class="tab-pane fade in">
                <h3>Add city</h3>
                <p>
                <form action="{{route('follow')}}" method="GET">
                    <div class="form-group">
                        <label for="new">Name</label>
                        <input type="text" name="new" id="new" value="" class="form-control">
                        @if($errors->has('new'))
                        <span class="text-danger"> {{ $errors->first('new') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
                </p>
            </div>
        </div>
        @else
        Brak miast
        @endif
    </div>
</div>




@endsection