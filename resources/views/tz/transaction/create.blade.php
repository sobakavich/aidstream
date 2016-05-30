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
                    {!! Form::hidden('transaction[0][transaction_type][0][transaction_type_code]', $transactionType) !!}
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
                                {!! Form::label('transaction[0][reference]', 'Transaction Reference', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][reference]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][transaction_date]', 'Transaction Date', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][transaction_date]', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! Form::text('transaction[0][value][0][date]', null, ['class' => 'hidden']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
                                {!! Form::select('transaction[0][value][0][currency]', ['' => 'Select one of the following.'] + $currency, null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][description][0][narrative][0][narrative]', 'Description', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][description][0][narrative][0][narrative]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! Form::hidden('transaction[0][description][0][narrative][0][language]', null) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('transaction[0][provider_organization][0][narrative][0][narrative]', 'Receiver Organization', ['class' => 'control-label required']) !!}
                                {!! Form::text('transaction[0][provider_organization][0][narrative][0][narrative]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! Form::text('transaction[0][provider_organization][0][narrative][0][language]', null, ['class' => 'hidden']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::submit('Save', ['class' => 'pull-right', 'id' => 'submit-transaction']) !!}

                    @if($transactionType == 1)
                        <button type="button" id="add-more-transaction" class="add-more">Add More Incoming Funds</button>
                    @elseif($transactionType == 3)
                        <button type="button" id="add-more-transaction" class="add-more">Add More Disbursements</button>
                    @elseif($transactionType == 4)
                        <button type="button" id="add-more-transaction" class="add-more">Add More Expenditure</button>
                    @endif

                    {!! Form::close() !!}

                    <div class="hidden" id="transaction-form-clone">
                        {!! Form::hidden('transaction[index][transaction_type][0][transaction_type_code]', $transactionType) !!}
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-sm-6">
                                    {!! Form::label('transaction[index][reference]', 'Transaction Reference', ['class' => 'control-label required']) !!}
                                    {!! Form::text('transaction[index][reference]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction[index][transaction_date]', 'Transaction Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text('transaction[index][transaction_date]', null, ['class' => 'form-control sbs', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction[index][value][0][amount]', 'Amount', ['class' => 'control-label required']) !!}
                                    {!! Form::text('transaction[index][value][0][amount]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    {!! Form::text('transaction[index][value][0][date]', null, ['class' => 'hidden']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction[index][value][0][currency]', 'Currency', ['class' => 'control-label required']) !!}
                                    {!! Form::select('transaction[index][value][0][currency]', ['' => 'Select one of the following.'] + $currency, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction[index][description][0][narrative][0][narrative]', 'Description', ['class' => 'control-label required']) !!}
                                    {!! Form::text('transaction[index][description][0][narrative][0][narrative]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    {!! Form::hidden('transaction[index][description][0][narrative][0][language]', null) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('transaction[index][provider_organization][0][narrative][0][narrative]', 'Receiver Organization', ['class' => 'control-label required']) !!}
                                    {!! Form::text('transaction[index][provider_organization][0][narrative][0][narrative]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    {!! Form::text('transaction[index][provider_organization][0][narrative][0][language]', null, ['class' => 'hidden']) !!}
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" onclick="removeBlock(this)">Remove</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('/js/tz/transaction.js') }}"></script>
@stop
