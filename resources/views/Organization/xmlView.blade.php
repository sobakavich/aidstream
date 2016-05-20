@extends('app')

@section('title', 'Organization Data')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="element-panel-heading">
                    <div>
                        <div class="org-title"><span class="pull-left">Organization</span></div>
                        <div class="panel-action-btn panel-xml-btn">
                    <span><a href="{{ route('organization.show', $orgId) }}" class="back-to-organization">Back to
                            Organization</a></span>
                    <span><a href="{{route('download.organizationXml', ['orgId' => $orgId])}}" class="btn btn-primary">Download
                            Xml</a></span>
                        </div>
                    </div>
                </div>
                <div class="xml-info">
                    <ul>
                        @forelse($messages as $message)
                            <li class="error">{!! $message !!}</li>
                        @empty
                            <li class="success">Validated!!</li>
                        @endforelse
                    </ul>
                    <br/>
                    @foreach($xmlLines as $key => $line)
                        {{--*/ $number = $key + 1; /*--}}
                        <div id="{{ $number }}"
                             style="{{ array_key_exists($number, $messages) ? 'color:#e15454;background:#eee;': ''  }}">
                            <strong>{{ $number }}</strong>{{ $line }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
