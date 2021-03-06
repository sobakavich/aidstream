@extends('settings.settings')
@section('panel-body')
    <div class="panel-body">
        <div class="create-form settings-form">
            <div class="settings-checkall-wrapper">
                <h2>Default Values</h2>
                <p>These values will be used in the xml files which is published to the IATI Registry. You have the option to override the activities.</p>
            </div>
            <div id="default_values">
                {!! form_start($form) !!}
                <h2>Default for all data</h2>
                <div class="col-md-12">
                    {!! form_until($form, 'default_language') !!}
                </div>
                <h2>Default for Activity data</h2>
                <div class="col-md-12">
                    {!! form_until($form, 'linked_data_uri') !!}
                </div>
                <div class="col-md-12">
                    {!! form_until($form, 'default_flow_type') !!}
                </div>
                <div class="col-md-12">
                    {!! form_until($form, 'default_aid_type') !!}
                </div>
                <div class="col-md-12">
                    {!! form_until($form,'default_tied_status') !!}
                </div>
                @if(Session::get('version') != 'V201')
                    <div class="col-md-12">
                        {!! form_until($form, 'humanitarian') !!}
                    </div>
                @endif
                {!!  form_end($form) !!}
            </div>
        </div>
    </div>
@stop
@section('foot')
    <script src="/js/userOnBoarding.js"></script>
    <script>
        $(window).load(function () {
                    @if(session('first_login') && auth()->user()->isAdmin())
            var stepNumber = location.hash.replace('#', '');
            if (stepNumber == 5) {
                var completedSteps = [{!! json_encode((array)$completedSteps) !!}];
                $('.introjs-hints').css('display', 'none');
                UserOnBoarding.settingsTour(completedSteps);
            }
            @endif
            UserOnBoarding.validateDefaultValues();
        });
    </script>
@endsection
