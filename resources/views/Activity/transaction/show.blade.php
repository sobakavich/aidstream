@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$activity->IdentifierTitle}}</div>
                    <strong><h3>Element Detail</h3></strong>
                    <div class="panel-body">
                        <div>Ref: {{$transactionDetail['reference']}}</div>
                        <strong>Transaction Type</strong>
                        <div>Code: {{$transactionDetail['transaction_type'][0]['transaction_type_code']}}</div>
                        <strong>Provider Organization</strong>
                        {{--*/ $providerOrg = $transactionDetail['provider_organization'][0] /*--}}
                        <div>Ref: {{$providerOrg['organization_identifier_code']}}</div>
                        <div>Provider_activity_id: {{$providerOrg['provider_activity_id']}}</div>
                        <div>Narrative text: {{$providerOrg['narrative'][0]['narrative']}}</div>
                        <strong>Value</strong>
                        {{--*/ $value = $transactionDetail['value'][0] /*--}}
                        <div>Amount: {{$value['amount'] }}</div>
                        <div>Value date: {{$value['date'] }}</div>
                        <div>Currency: {{$value['currency'] }}</div>
                        <strong>Description</strong>
                        <div>Narrative text: {{$transactionDetail['description'][0]['narrative'][0]['narrative']}}</div>
                        <strong>Transaction Date</strong>
                        <div>Date: {{$transactionDetail['transaction_date'][0]['date']}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
