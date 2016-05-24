@if(!emptyOrHasEmptyTemplate($budgets))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.budget')</div>
        @foreach( groupBudgetElements($budgets , 'budget_type') as $key => $budgets)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('BudgetType' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($budgets as $budget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $budget) !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.period')</dt>
                                <dd>{!! getBudgetInformation('period' , $budget) !!}</dd>
                            </dl>
                            @if(session('version') != 'V201')
                                <dl>
                                    <dt>@lang('activityView.status')</dt>
                                    <dd>{!! getBudgetInformation('status' , $budget) !!}</dd>
                                </dl>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.budget.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'budget'])}}" class="delete pull-right">remove</a>
    </div>
@endif
