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
                        @if (isset($filename))
                            <div class="element-panel-heading-info">
                                <span>{{ $filename }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper transaction-wrapper">
                    <form action="{{ route('import.save-valid-transactions', $id) }}" method="POST">
                        {{ csrf_field() }}

                        <div id="transaction-status-holder" style="display: none;"></div>
                        <input type="submit" id="saveValidatedTransactions" style="display: none;">
                    </form>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var filename = "{{ $filename }}";
        var route = "{{ route('import.confirm-transactions') }}";
    </script>
    <script src="{{ asset('/js/import/transactionImport.js') }}"></script>
@stop
