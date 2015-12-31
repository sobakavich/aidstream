@extends('app')
@section('content')
    @if(count($errors)>0)
        <div class="alert alert-warning">
            @foreach($errors->all() as $error)
                <ul>
                    <li>{{$error}}</li>
                </ul>
            @endforeach
        </div>
    @endif
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                     <div class="panel-content-heading panel-title-heading">
                            Upload Activities
                            <a href="{{ route('activity.index') }}" class="btn btn-primary pull-right">Back to Activity List</a>
                        </div>
                     <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                        <div class="panel panel-default panel-upload">                   
                            <div class="panel-body">
                                <div class="create-form">
                                    {!! form($form) !!}
                                </div>
                                <div class="download-transaction-wrap">
                                    <a href="/download-activity-template" class="btn btn-primary btn-form btn-submit">Download Activity Template</a>
                                    <div>Contains Simplified information about Activity.</div>
                                </div>
                         </div>
                    </div>
                     
              </div>
        </div>
    </div>
@stop