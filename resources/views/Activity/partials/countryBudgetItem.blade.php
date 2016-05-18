@if(!emptyOrHasEmptyTemplate($countryBudgetItems))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.country_budget_items')</dt>
                <dd>
                    @foreach($countryBudgetItems[0]['budget_item'] as $budgetItems)
                        <li>{{ getCountryBudgetItems($countryBudgetItems[0]['vocabulary'], $budgetItems) }}</li>
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                        <dl class="hidden-info">
                            <dl>@lang('activityView.description')
                                : {!!  getFirstNarrative($budgetItems['description'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($budgetItems['description'][0]['narrative'])])
                            </dl>

                            <dl>@lang('activityView.vocabulary')
                                : {!! getCodeNameWithCodeValue('BudgetIdentifierVocabulary' ,$countryBudgetItems[0]['vocabulary'] , -4 ) !!}
                            </dl>
                        </dl>
                    @endforeach
                </dd>
            {{--<a href="{{route('activity.country-budget-items.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'country_budget_items'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
