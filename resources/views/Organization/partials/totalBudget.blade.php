@if(!emptyOrHasEmptyTemplate($total_budget))
    <div class="activity-element-wrapper">
        @if(session('version') != 'V201')
            <div class="title">@lang('activityView.total_budget')</div>
        @endif
        @foreach(groupActivityElements($total_budget , 'status') as  $key => $totalBudgets)
            <div class="activity-element-list">
                @if(session('version') != 'V201')
                    <div class="activity-element-label">{{ $getCode->getCodeNameOnly('BudgetStatus' , $key) }}</div>
                @else
                    <div class="activity-element-label">@lang('activityView.total_budget')</div>
                @endif
                <div class="activity-element-info">
                    @foreach($totalBudgets as $totalBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $totalBudget) !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>                   
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.period')</dt>
                                <dd>{!! checkIfEmpty(getBudgetInformation('period' , $totalBudget)) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.budget_line')</dt>
                                @foreach($totalBudget['budget_line'] as $budgetLine)
                                    <dd>
                                        <li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                    <dd>@lang('activityView.reference')
                                        : {!! checkIfEmpty($budgetLine['reference']) !!}
                                    </dd>

                                    <dd>@lang('activityView.narrative')
                                        : {!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}
                                        @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])
                                    </dd>
                                @endforeach
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <a href="{{ url('/organization/' . $orgId . '/total-budget') }}" class="edit-element">edit</a>
@endif
