@extends('layouts.app')

@section('content')

<div class="container">
    <div id="allcontent">
        <div>
            <h2>Weather Forecast</h2>
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

                <h3>{{$city->name}}, {{$city->country}}</h3>
                <p>
                    @foreach($weatherStatus[$loop->index]->current->weather as $weather)
                    <img src="{{asset('storage/icons/'. $weather->icon .'.png')}}">
                    {{$weatherStatus[$loop->index]->current->temp}} °C <br>
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
                <table>
                    @foreach($weatherStatus[$loop->index]->daily as $daily)
                    <tr>
                        <td>{{gmdate("D, M d", $daily->dt);}}</td>
                        <td><img src="{{asset('storage/icons/'. $daily->weather[0]->icon .'.png')}}" height="40px">
                            {{$daily->temp->max }} /{{$daily->temp->min }} °C
                        </td>
                        <td class="pl-2 clouds">{{$daily->weather[0]->description}}</td>
                    </tr>
                    @endforeach
                </table>

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