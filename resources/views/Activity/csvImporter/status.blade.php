@extends('app')

@section('title', 'Import Status')

@section('content')
    {{--for Sweta--}}
    <style>
        input#cancel-import {
            position: absolute;
            right: 115px;
            top: -50px;
            z-index: 1;
        }

        #checkAll {
            position: absolute;
            right: 120px;
            top: 30px;
            z-index: 1;
        }
    </style>
    {{--for Sweta--}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper upload-activity-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        Import Activities
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper status-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="status-show-block">
                                <input type="checkbox">
                                <span></span>
                                <label>Show</label>
                                <select class="tab-select">
                                    <option data-select="all">All</option>
                                    <option data-select="valid">Valid</option>
                                    <option data-select="invalid">Invalid</option>
                                </select>
                            </div>
                            <form action="{{ route('activity.cancel-import') }}" method="POST" id="cancel-import">
                                {{ csrf_field() }}
                                <input type="button" class="btn_confirm hidden" id="cancel-import" data-title="Confirmation" data-message="Are you sure you want to Cancel Activity Import?" value="Cancel">
                            </form>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane" id="all">
                                    <div class="all-data"></div>
                                </div>

                                <div role="tabpanel" class="tab-pane active" id="valid">
                                    <div id="checkAll" class="hidden">
                                        <label>
                                            <input type="checkbox" id="check-all" >Check All
                                            <span></span>
                                        </label>
                                    </div>
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
    <script src="{{ asset('js/csvImporter/accordion.js') }}"></script>
    <script src="{{ asset('js/csvImporter/csvImportStatus.js') }}"></script>
    <script src="{{ asset('js/csvImporter/selectTabs.js') }}"></script>
@stop
