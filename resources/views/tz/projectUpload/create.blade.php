@extends('tz.base.sidebar')

@section('title', 'Upload Project')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Project Upload</div>
            </div>

            <div class="panel-body">
                <div class="create-activity-form">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! Form::open(['route' => 'project-upload.store']) !!}
                            {!! Form::file('project_file') !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{route('project.templateDownload')}}" class="btn btn-primary">Download Project Template</a>
                </div>

            </div>
        </div>
    </div>
    </div>
@stop
