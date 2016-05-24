@if(!emptyOrHasEmptyTemplate($countryBudgetItems))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.country_budget_items')</div>
            <div class="activity-element-info">
                @foreach($countryBudgetItems[0]['budget_item'] as $budgetItems)
                    <li>{{ getCountryBudgetItems($countryBudgetItems[0]['vocabulary'], $budgetItems) }}</li>
                    <div class="toggle-btn">
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                    </div>
                    <div class="more-info">
                        <dl>
                            <dt>@lang('activityView.description')</dt>
                            <dd>{!!  getFirstNarrative($budgetItems['description'][0]) !!}</dd>
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($budgetItems['description'][0]['narrative'])])
                        </dl>
                        <dl>
                            <dt>@lang('activityView.vocabulary')</dt>
                            <dd>{!! getCodeNameWithCodeValue('BudgetIdentifierVocabulary' ,$countryBudgetItems[0]['vocabulary'] , -4 ) !!}</dd>
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>
        {{--<a href="{{route('activity.country-budget-items.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'country_budget_items'])}}" class="delete pull-right">remove</a>--}}
    </div>
@endif
