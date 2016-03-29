@extends('app')

@section('title', 'Create Activity')
@inject('code', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel panel-default panel-create">
                    <div class="panel-content-heading panel-title-heading">
                        <div>Add Activity</div>
                    </div>
                    <div class="panel-body">
                        <div class="create-new-activity-form">
                            <div class="hidden" id="reporting_organization_identifier">{{ $identifier }}</div>
                            {!! form($form) !!}
                        </div>
                        <div class="collection-container hidden"
                             data-prototype="{{ form_row($form->activity->prototype()) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection