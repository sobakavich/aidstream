@if(!emptyOrHasEmptyTemplate($total_budget))
    <dl class="dl-horizontal">
        <div class="title">@lang('activityView.total_budget')</div>

        @foreach(groupActivityElements($total_budget , 'status') as  $key => $totalBudgets)
            @if(session('version') != 'V201')
                <dt>{{ $getCode->getCodeNameOnly('BudgetStatus' , $key) }}</dt>
            @endif
            <dd>
                @foreach($totalBudgets as $totalBudget)
                    <li>{!! getBudgetInformation('currency_with_valuedate' , $totalBudget) !!}</li>
                    <a href="#" class="show-more-info">Show more info</a>
                    <a href="#" class="hide-more-info hidden">Hide more info</a>                   
                    <div class="more-info-hidden">
                        <dl>@lang('activityView.period')
                            : {!! checkIfEmpty(getBudgetInformation('period' , $totalBudget)) !!}
                        </dl>

                        <dl>@lang('activityView.budget_line'):
                            @foreach($totalBudget['budget_line'] as $budgetLine)
                                <li style="list-style-type:square">{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                <dl>@lang('activityView.reference')
                                    : {!! checkIfEmpty($budgetLine['reference']) !!}
                                </dl>

                                <dl>@lang('activityView.narrative')
                                    : {!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])
                                </dl>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </dd>
        @endforeach
        {{--@foreach(groupActivityElements($))--}}
    </dl>

@endif
