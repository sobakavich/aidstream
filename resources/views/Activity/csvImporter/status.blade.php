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
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper element-upload-wrapper status-wrapper">
                    <div class="panel panel-default panel-upload">
                        <div class="panel-body">
                            <div class="status-show-block">
                                <input type="checkbox">
                                <span></span>
                                <label for="">Show</label>
                                <div class="btn-group">
                                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                        Action <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#valid" role="tab"
                                               data-toggle="tab">Valid</a>
                                        </li>
                                        <li><a href="#invalid" role="tab" data-toggle="tab">Invalid</a>
                                        </li>
                                    </ul>
                                </div>
                                {{--<ul class="nav nav-tabs" role="tablist">--}}
                                {{--<li role="presentation" class="active"><a href="#valid" aria-controls="valid" role="tab" data-toggle="tab">Valid</a></li>--}}
                                {{--<li role="presentation"><a href="#invalid" aria-controls="invalid" role="tab" data-toggle="tab">Invalid</a></li>--}}
                                {{--</ul>--}}
                            </div>
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
            $(".invalid-data .panel-default .panel-heading").on('click', 'label', function () {
                $(this).children('.data-listing').slideToggle();
            });
        };

        $('.dropdown-menu li a').on('shown', function (e) {
            $('.dropdown-menu li.active').removeClass('active');
            $(this).parent('li').addClass('active');
        });

    </script>
    <script src="{{ asset('js/csvImporter/csvImportStatus.js') }}"></script>
@stop
