@extends('app')

@section('title', 'Settings')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
                @include('includes.response')
                <div class="panel panel-default">
                    <div class="element-panel-heading">
                        <div>
                            Settings
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="create-form settings-form">
                            {!! form($form) !!}
                        </div>
                        <div class="collection-container hidden"
                             data-prototype="{{ form_row($form->reporting_organization_info->prototype()) }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
