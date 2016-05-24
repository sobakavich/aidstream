@if(!emptyOrHasEmptyTemplate($recipient_country_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.recipient_country_budget')</div>
        @foreach(groupByCountry($recipient_country_budget) as $key => $countryBudgets)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {!! $key !!}
                </div>
                <div class="activity-element-info">
                    @foreach($countryBudgets as $countryBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $countryBudget) !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.period'):</dt>
                                <dd>{!! checkIfEmpty(getBudgetInformation('period' , $countryBudget)) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.description'):</dt>
                                <dd>
                                    {!! getFirstNarrative($countryBudget['recipient_country'][0]) !!} <br>
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($countryBudget['recipient_country'][0]['narrative'])])
                                </dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.budget_line'):</dt>
                            </dl>

                            <dl>
                                @foreach($countryBudget['budget_line'] as $budgetLine)
                                    <dd>
                                        <li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                        @lang('activityView.reference'):
                                        {!! checkIfEmpty($budgetLine['reference']) !!}

                                        <br>

                                        @lang('activityView.narrative'):
                                        {!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}
                                        @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])
                                    </dd>
                                @endforeach
                            </dl>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
        <a href="{{ url('/organization/' . $orgId . '/recipient-country-budget') }}" class="edit-element">edit</a>
    </div>
@endif
