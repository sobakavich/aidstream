@extends('app')

@section('title', 'Organization Data')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="element-panel-heading">
                    <div class="element-panel-heading">
                        <div><span class="pull-left">Organization</span></div>
                        <span><a href="{{route('download.organizationXml', ['orgId' => $orgId])}}" class="btn btn-primary">Download Xml</a></span>
                        <span><a href="{{ route('organization.show', $orgId) }}" class="btn btn-primary">View Organization</a></span>
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
