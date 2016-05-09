@extends('app')

@section('title', 'Activity Results - ' . $activityData->IdentifierTitle)

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper result-show">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Document Link</span>
                        <div class="element-panel-heading-info">
                            <span>{{$activityData->IdentifierTitle}}</span>
                        </div>
                        <div class="panel-action-btn">
                            <a href="{{route('activity.show',$id)}}" class="btn btn-primary">View Activity</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    @include('Activity.partials.documentLink')
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
