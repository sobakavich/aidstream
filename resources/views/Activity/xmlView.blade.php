@extends('app')

@section('title', 'Activity Data')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="element-panel-heading">
                    <div>
                        <span>{{ $activityDataList['title'] ? $activityDataList['title'][0]['narrative'] : 'No Title' }}</span>
                        <div class="element-panel-heading-info">
                            <span>{{$activityDataList['identifier']['iati_identifier_text']}}</span>
                            <span class="last-updated-date">Last Updated on: {{changeTimeZone($activityDataList['updated_at'], 'M d, Y H:i')}}</span>
                            <span><a href="{{route('download.activityXml', ['activityId' => $activityId])}}" class="btn btn-primary">Download Xml</a></span>
                            <span><a href="{{ route('activity.show', $activityId) }}" class="btn btn-primary">View Activity</a></span>
                        </div>
                    </div>
                </div>
                <ul>
                    @forelse($messages as $message)
                        <li>{!! $message !!}</li>
                    @empty
                        <li>Validated!!</li>
                    @endforelse
                </ul>
                <br/>
                @foreach($xmlLines as $key => $line)
                    {{--*/ $number = $key + 1; /*--}}
                    <div id="{{ $number }}"><strong style="{{ array_key_exists($number, $messages) ? 'color:red': ''  }}">{{ $number }}</strong>{{ $line }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
