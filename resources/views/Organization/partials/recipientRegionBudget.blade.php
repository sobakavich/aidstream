@if(!emptyOrHasEmptyTemplate($recipient_region_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.recipient_region_budget')</div>
        @foreach(groupActivityElements($recipient_region_budget , 'status') as  $key => $recipientRegionBudgets)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $getCode->getCodeNameOnly('BudgetStatus' , $key) }}
                </div>
                <div class="activity-element-info">
                    @foreach($recipientRegionBudgets as $recipientRegionBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $recipientRegionBudget) !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.period'):</dt>
                                <dd>{!! checkIfEmpty(getBudgetInformation('period' , $recipientRegionBudget)) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.recipient_region'):</dt>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.vocabulary'):</dt>
                                <dd> {!! getCodeNameWithCodeValue('RegionVocabulary' , $recipientRegionBudget['recipient_region'][0]['vocabulary'] , -4) !!} </dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.vocabulary_uri'):</dt>
                                <dd> {!! getClickableLink($recipientRegionBudget['recipient_region'][0]['vocabulary_uri']) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.code'):</dt>
                                <dd>{!! getCodeNameWithCodeValue('Region' , $recipientRegionBudget['recipient_region'][0]['code'] , -5) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.description'):</dt>
                                <dd>
                                    {!! getFirstNarrative($recipientRegionBudget['recipient_region'][0]) !!}<br>
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientRegionBudget['recipient_region'][0]['narrative'])])
                                </dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.budget_line'):</dt>
                            </dl>

                            {{--Sweta did यहा पनि है--}}
{{--===================================================================--}}

                            {{--<dl>--}}
                            {{--@foreach($recipientRegionBudget['budget_line'] as $budgetLine)--}}
                            {{--<dd>--}}
                            {{--<li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>--}}
                            {{--@lang('activityView.reference'):--}}
                            {{--{!! checkIfEmpty($budgetLine['reference']) !!}--}}

                            {{--<br>--}}

                            {{--@lang('activityView.narrative'):--}}
                            {{--{!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}--}}
                            {{--@include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])--}}
                            {{--</dd>--}}
                            {{--@endforeach--}}

                            {{--</dl>--}}

{{--===================================================--}}

                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>








    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Recipient Region Budget</div>
            <a href="{{ url('/organization/' . $orgId . '/recipient-region-budget') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body row panel-level-2">
            @foreach($recipient_region_budget as $recipientRegionBudget)
                <div class="panel-heading">
                    <div class="activity-element-title">{{ $code->getActivityCodeName('BudgetStatus', $recipientRegionBudget['status'])}}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Status:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $code->getActivityCodeName('BudgetStatus', $recipientRegionBudget['status'])}}</div>
                                    </div>
                                    @foreach($recipientRegionBudget['recipient_region'] as $recipientRegion)
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Vocabulary</div>
                                            <div class="col-xs-12 col-xs-8">{{ $code->getActivityCodeName('RegionVocabulary', $recipientRegion['vocabulary']) }}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Vocabulary Uri</div>
                                            <div class="col-xs-12 col-xs-8">{{ $recipientRegion['vocabulary_uri'] }}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Code</div>
                                            <div class="col-xs-12 col-xs-8">{{ $code->getActivityCodeName('Region', $recipientRegion['code']) }}</div>
                                        </div>
                                        @foreach($recipientRegion['narrative'] as $recipientRegionNarrative)
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-xs-4">Text:</div>
                                                <div class="col-xs-12 col-xs-8">{{ $recipientRegionNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientRegionNarrative['language']) }}</div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period Start</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudget['period_start'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period End</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudget['period_end'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Value</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Amount:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudget['value'][0]['amount']}}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Value Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudget['value'][0]['value_date']) }}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Currency:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudget['value'][0]['currency']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Budget Line</div>
                                </div>
                                <div class="panel-drop-body">
                                    @foreach($recipientRegionBudget['budget_line'] as $recipientRegionBudgetLine)
                                        <div class="panel panel-default">
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Reference:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLine['reference']}}</div>
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
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLine['value'][0]['amount']}}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Value Date:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ formatDate($recipientRegionBudgetLine['value'][0]['value_date']) }}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Currency:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLine['value'][0]['currency']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Narrative</div>
                                            </div>
                                            <div class="panel-element-body">
                                                @foreach($recipientRegionBudgetLine['narrative'] as $recipientRegionBudgetLineNarrative)
                                                    <div class="panel-body panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                                            <div class="col-xs-12 col-xs-8">{{ $recipientRegionBudgetLineNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $recipientRegionBudgetLineNarrative['language']) }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif