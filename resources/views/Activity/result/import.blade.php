@extends('app')

@section('title', 'Import Results')

@section('content')

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        Import Results
                    </div>
                    <div>
                        <a href="{{ route('activity.result.index', [$activityId]) }}" class="pull-right back-to-list">
                            <span class="glyphicon glyphicon-triangle-left"></span>Back to Result List
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="create-form">
                                {!! form($form) !!}
                            </div>
                            <div class="download-transaction-wrap">
                                <a href="{{route('download.result-template')}}"
                                   class="btn btn-primary btn-form btn-submit">Download Result Template</a>
                                <div>
                                    Result Template info.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
