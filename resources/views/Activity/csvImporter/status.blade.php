@extends('app')

@section('title', 'Import Status')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        Import Activities
                    </div>
                    <div>

                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper status-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#valid" aria-controls="valid" role="tab" data-toggle="tab">Valid</a></li>
                                        <li role="presentation"><a href="#invalid" aria-controls="invalid" role="tab" data-toggle="tab">Invalid</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="valid">
                                            <form action="{{ route('activity.import-validated-activities') }}" method="POST">
                                                {{ csrf_field() }}
                                                <div class="valid-data"></div>

                                                <input type="submit" class="hidden" id="submit-valid-activities" value="Import">
                                            </form>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="invalid">
                                            <div class="invalid-data"></div>
                                        </div>
                                    </div>
                                </div>

                            <div class="download-transaction-wrap">
                                <div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var test = function () {
            $(".invalid-data .panel-default .panel-heading").on('click','label',function() {
                $(this).children('.data-listing').slideToggle();
            });
        };
    </script>
    <script src="{{ asset('js/csvImporter/csvImportStatus.js') }}"></script>
@stop
