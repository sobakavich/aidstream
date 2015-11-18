@extends('app')

@section('content')

    {{Session::get('message')}}

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Data</div>

                    <div class="panel-body">

                        <ol class="breadcrumb">
                            <?php
                            $status_label = ['Draft', 'Completed', 'Verified', 'Published'];
                            $btn_status_label = ['Complete', 'Verify', 'Publish'];
                            $btn_text = $activity_workflow > 2 ? "" : $btn_status_label[$activity_workflow];
                            ?>
                            @foreach($status_label as $key => $val)
                                @if($key == $activity_workflow)
                                    <li class="active">{{ $val }}</li>
                                @else
                                    <li><a href="#">{{ $val }}</a></li>
                                @endif
                            @endforeach
                        </ol>
                        @if($btn_text != "")
                            <form method="POST" id="change_status" action="{{ url('/activity/' . $id . '/update-status') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="activity_workflow" value="{{ $activity_workflow + 1 }}">
                                @if($activity_workflow == 2)
                                    <input type="button" value="{{ $btn_text }}" class="btn_confirm"
                                           data-title="Confirmation" data-message="Are you sure you want to Publish?">
                                @else
                                    <input type="submit" value="{{ $btn_text }}">
                                @endif
                            </form>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
