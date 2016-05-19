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


    </div>









    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Recipient Country</div>
            <a href="{{ url('/organization/' . $orgId . '/recipient-country-budget') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-level-2">
            @foreach($recipient_country_budget as $recipientCountryBudget)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        Code: {{ $code->getOrganizationCodeName('Country', $recipientCountryBudget['recipient_country'][0]['code'])}}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Code:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Country', $recipientCountryBudget['recipient_country'][0]['code'])}}</div>
                                    </div>
                                    @foreach($recipientCountryBudget['recipient_country'][0]['narrative'] as $recipientCountryNarrative)
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientCountryNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientCountryNarrative['language']) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Value</div>
                                </div>
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Amount:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['amount']}}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Value Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudget['value'][0]['value_date']) }}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Currency:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['currency']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period Start</div>
                                </div>
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudget['period_start'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period End</div>
                                </div>
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudget['period_end'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <div class="activity-element-title">Budget Line</div>
                            </div>
                            <div class="panel-body">
                                @foreach($recipientCountryBudget['budget_line'] as $recipientCountryBudgetLine)
                                    <div class="panel-heading">
                                        <div class="activity-element-title">{{ $recipientCountryBudgetLine['reference']}}</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Reference:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['reference']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Value</div>
                                            </div>
                                            <div class="panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Text:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['amount']}}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Value Date:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ formatDate($recipientCountryBudgetLine['value'][0]['value_date']) }}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Currency:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['currency']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Narrative</div>
                                            </div>
                                            <div class="panel-element-body">
                                                @foreach($recipientCountryBudgetLine['narrative'] as $recipientCountryBudgetLineNarrative)
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="col-xs-12 col-xs-4">Text:</div>
                                                        <div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLineNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientCountryBudgetLineNarrative['language']) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
