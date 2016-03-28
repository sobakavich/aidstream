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
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper transaction-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row form-group">

                                @if(count($commitment) > 0)
                                    <div class="col-sm-12">Commitment <a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,2))}}">Edit</a></div>
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
                                                    <td>{{$data['date'][0]['date']}}</td>
                                                    <td><a class="delete"
                                                           href="{{ url(sprintf('activity/%s/transaction/%s/delete', $activity->id,$data['id'])) }}">Delete</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col-sm-12">Commitment <a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,2))}}">Add</a></div>
                                    <div class="text-center">
                                        You haven’t added any transactions yet.
                                    </div>
                                @endif
                            </div>

                            <div class="row form-group">
                                @if(count($disbursement) > 0)
                                    <div class="col-sm-12">Disbursement <a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,3))}}">Edit</a></div>
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
                                    <div class="col-sm-12">Disbursement <a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,3))}}">Add</a></div>
                                    <div class="text-center">
                                        You haven’t added any transactions yet.
                                    </div>
                                @endif
                            </div>

                            <div class="row form-group">

                                @if(count($expenditure) > 0)
                                    <div class="col-sm-12">Expenditure <a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,4))}}">Edit</a></div>
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
                                    <div class="col-sm-12">Expenditure <a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,4))}}">Add</a></div>
                                    <div class="text-center">
                                        You haven’t added any transactions yet.
                                    </div>
                                @endif
                            </div>

                            <div class="row form-group">

                                @if(count($incomingFund) > 0)
                                    <div class="col-sm-12">Incoming Funds<a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/edit', $activity->id,1))}}">Edit</a></div>
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
                                    <div class="col-sm-12">Incoming Funds<a class="btn btn-primary pull-right" href="{{url(sprintf('activity/%s/transaction/%s/create', $activity->id,1))}}">Add</a></div>
                                    <div class="text-center">
                                        You haven’t added any transactions yet.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop