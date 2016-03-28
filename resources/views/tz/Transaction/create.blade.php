@extends('app')

@section('title', 'Create Activity Transaction')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Add Transaction</span>
                    </div>
                </div>
                <div class="col-xs-12 transaction-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="create-transaction-form">
                                {!! form($form) !!}
                            </div>
                            <div class="collection-container hidden"
                                 data-prototype="{{ form_row($form->transaction->prototype()) }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
