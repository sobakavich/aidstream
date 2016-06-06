@extends('tz.base.sidebar')

@section('title', 'Project')

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="element-panel-heading">
            @include('tz.project.partials.show.project-heading')
        </div>
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper fullwidth-wrapper">
            @include('tz.project.partials.show.workflow-status')

            <div class="panel panel-default panel-element-detail element-show">
                @include('tz.project.partials.show.identifier')
                @include('tz.project.partials.show.title')
                @include('tz.project.partials.show.description')
                @include('tz.project.partials.show.project-status')
                @include('tz.project.partials.show.sector')
                @include('tz.project.partials.show.project-date')
                @include('tz.project.partials.show.recipient-country')
                @include('tz.project.partials.show.location')
                @include('tz.project.partials.show.participating-organization')
                @include('tz.project.partials.show.result-and-outcomes')
                @include('tz.project.partials.show.annual-reports')
                @include('tz.project.partials.show.budget')
                @include('tz.project.partials.show.disbursements')
                @include('tz.project.partials.show.expenditure')
                @include('tz.project.partials.show.incoming-funds')
            </div>
        </div>
    </div>
@endsection
