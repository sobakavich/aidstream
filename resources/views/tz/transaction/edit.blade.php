@extends('tz.base.sidebar')

@section('title', 'Edit Transaction')
@inject('code', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Edit Transaction</div>
            </div>
            <div class="panel-body">
                <div class="create-activity-form">

                    {!! Form::open(['route' => ['transaction.update', $projectId, $transactionType], 'method' => 'POST']) !!}
                    {!! Form::hidden('activity_id', $projectId) !!}

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

                            @foreach($transactions as $key => $transaction)
                                {!! Form::hidden("transaction[$key][id]", $transactions[$key]['id']) !!}
                                {!! Form::hidden("transaction[$key][transaction_type][0][transaction_type_code]", $transactionType) !!}
                                <div class="col-sm-6">
                                    {!! Form::label('reference', 'Transaction Reference', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][reference]", $transaction['transaction']['reference'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction_date', 'Transaction Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][transaction_date][0][date]", $transaction['transaction']['transaction_date'][0]['date'], ['class' => 'form-control', 'required' => 'required', 'id' => 'datepicker']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('amount', 'Amount', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][value][0][amount]", $transaction['transaction']['value'][0]['amount'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('currency', 'Currency', ['class' => 'control-label required']) !!}
                                    {!! Form::select("transaction[$key][value][0][currency]", ['' => 'Select one of the following.'] + $currency, $transaction['transaction']['value'][0]['currency'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('description', 'Description', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][description][0][narrative][0][narrative]", $transaction['transaction']['description'][0]['narrative'][0]['narrative'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('receiver_org', 'Receiver Organization', ['class' => 'control-label required']) !!}
                                    {!! Form::text("transaction[$key][receiver_organization][0][narrative][0][narrative]", $transaction['transaction']['receiver_organization'][0]['narrative'][0]['narrative'], ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                            @endforeach


                        </div>
                    </div>
                    {!! Form::submit('Edit') !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
