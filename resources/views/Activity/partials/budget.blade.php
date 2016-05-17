@if(!emptyOrHasEmptyTemplate($budgets))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.budget')</dt>
                <dd>
                @foreach( groupBudgetElements($budgets , 'budget_type') as $key => $budgets)
                    <dt>{{ $getCode->getCodeNameOnly('BudgetType' , $key) }}</dt>
                    <dd>
                        @foreach($budgets as $budget)
                            <li>{!! getBudgetInformation('currency_with_valuedate' , $budget) !!}</li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.period')
                                    : {!! getBudgetInformation('period' , $budget) !!}
                                </dl>

                                @if(session('version') != 'V201')
                                    <dl>@lang('activityView.status')
                                        : {!! getBudgetInformation('status' , $budget) !!}
                                    </dl>
                                @endif
                            </dl>
                        @endforeach
                        <hr>
                        @endforeach
                    </dd>
            </dl>
            {{--<a href="{{route('activity.budget.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'budget'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
