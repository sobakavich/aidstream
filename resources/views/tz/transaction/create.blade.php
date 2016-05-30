@extends('tz.base.sidebar')

@section('title', 'Create Transaction')
@inject('code', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Add Transaction</div>
            </div>
            <div class="panel-body">
                <div class="create-activity-form">

                    {!! Form::open(['route' => ['project.transaction.store', $id], 'method' => 'POST']) !!}
                    {!! Form::hidden('activity_id', $id) !!}
                    {!! Form::hidden('transaction[0][transaction_type][0][transaction_type_code]', $transactionType) !!}
                    {!! Form::hidden('transaction[0][value][0][date]', null) !!}
                    {!! Form::hidden('transaction[0][description][0][narrative][0][language]', null) !!}
                    {!! Form::hidden('transaction[0][receiver_organization][0][organization_identifier_code]', null) !!}
                    {!! Form::hidden('transaction[0][receiver_organization][0][receiver_activity_id]', null) !!}
                    {!! Form::hidden('transaction[0][receiver_organization][0][narrative][0][language]', null) !!}
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                @if($transactionType == 1)
                                    Incoming Funds
                                @elseif($transactionType == 3)
                                    Disbursements
                                @elseif($transactionType == 4)
                                    Expenditure
                                @endif
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('reference', 'Transaction Reference', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][reference]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction_date', 'Transaction Date', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][transaction_date][0][date]', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'datepicker']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('amount', 'Amount', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('currency', 'Currency', ['class' => 'control-label required']) !!}
                                {!! Form::select('transaction[0][value][0][currency]', ['' => 'Select one of the following.'] + $currency, null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('description', 'Description', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][description][0][narrative][0][narrative]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('receiver_org', 'Receiver Organization', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][receiver_organization][0][narrative][0][narrative]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::submit('save') !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
