@extends('app')

@section('title', 'Activity Transactions - ' . $activity->IdentifierTitle)

@section('content')
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="element-panel-heading">
                    <div>
                        <span>Transactions</span>
                        <div class="element-panel-heading-info"><span>{{$activity->IdentifierTitle}}</span></div>
                    </div>
                </div>
                <div class="col-xs-12 transaction-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row form-group">

                                @if(count($commitment) > 0)
                                    <div class="col-sm-12 transaction-title">
                                        <span>Commitment</span>
                                        <a class="transaction-btn edit-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,2))}}"><span>Edit a Commitment</span></a>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Internal Ref</th>
                                                <th>Transaction Value</th>
                                                <th>Transaction Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($commitment as $data)
                                                <tr>
                                                    <td>{{$data['reference']}}</td>
                                                    <td>{{$data['amount']}}</td>
                                                    <td>{{$data['date']}}</td>
                                                    <td><a class="delete"
                                                           href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id,$data['id'])) }}">Delete</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col-sm-12 transaction-title">
                                        <span>Commitment</span>
                                        <a class="transaction-btn add-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,2))}}"><span>Add a Commitment</span></a>
                                    </div>
                                    {{--<div class="text-center">--}}
                                        {{--You haven’t added any transactions yet.--}}
                                    {{--</div>--}}
                                @endif
                            </div>

                            <div class="row form-group">
                                @if(count($disbursement) > 0)
                                    <div class="col-sm-12 transaction-title">
                                        <span>Disbursement</span>
                                        <a class="transaction-btn edit-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,3))}}"><span>Edit a Disbursement</span></a>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Internal Ref</th>
                                            <th>Transaction Value</th>
                                            <th>Transaction Date</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($disbursement as $data)
                                            <tr>
                                                <td>{{$data['reference']}}</td>
                                                <td>{{$data['amount']}}</td>
                                                <td>{{$data['date'][0]['date']}}</td>
                                                <td><a class="delete"
                                                       href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id,$data['id'])) }}">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col-sm-12 transaction-title">
                                        <span>Disbursement</span>
                                        <a class="transaction-btn add-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,3))}}"><span>Add a Disbursement</span></a>
                                    </div>
                                    {{--<div class="text-center">--}}
                                        {{--You haven’t added any transactions yet.--}}
                                    {{--</div>--}}
                                @endif
                            </div>

                            <div class="row form-group">

                                @if(count($expenditure) > 0)
                                    <div class="col-sm-12 transaction-title">
                                        <span>Expenditure</span>
                                        <a class="transaction-btn edit-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,4))}}"><span>Edit a Expenditure</span></a>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Internal Ref</th>
                                            <th>Transaction Value</th>
                                            <th>Transaction Date</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($expenditure as $data)
                                            <tr>
                                                <td>{{$data['reference']}}</td>
                                                <td>{{$data['amount']}}</td>
                                                <td>{{$data['date'][0]['date']}}</td>
                                                <td><a class="delete"
                                                       href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id,$data['id'])) }}">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col-sm-12 transaction-title">
                                        <span>Expenditure</span>
                                        <a class="transaction-btn add-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,4))}}"><span>Add a Expenditure</span></a></div>
                                    {{--<div class="text-center">--}}
                                        {{--You haven’t added any transactions yet.--}}
                                    {{--</div>--}}
                                @endif
                            </div>

                            <div class="row form-group">

                                @if(count($incomingFund) > 0)
                                    <div class="col-sm-12 transaction-title">
                                        <span>Incoming Funds</span>
                                        <a class="transaction-btn edit-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,1))}}"><span>Edit an Incoming Fund</span></a>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Internal Ref</th>
                                            <th>Transaction Value</th>
                                            <th>Transaction Date</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incomingFund as $data)
                                            <tr>
                                                <td>{{$data['reference']}}</td>
                                                <td>{{$data['amount']}}</td>
                                                <td>{{$data['date'][0]['date']}}</td>
                                                <td><a class="delete"
                                                       href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id,$data['id'])) }}">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col-sm-12 transaction-title">
                                        <span>Incoming Funds</span>
                                        <a class="transaction-btn add-transaction-type" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,1))}}"><span>Add an Incoming Fund</span></a></div>
                                    {{--<div class="text-center">--}}
                                        {{--You haven’t added any transactions yet.--}}
                                    {{--</div>--}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop