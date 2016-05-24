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
                                <dt>@lang('activityView.period')</dt>
                                <dd>{!! checkIfEmpty(getBudgetInformation('period' , $recipientRegionBudget)) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.vocabulary')</dt>
                                <dd> {!! getCodeNameWithCodeValue('RegionVocabulary' , $recipientRegionBudget['recipient_region'][0]['vocabulary'] , -4) !!} </dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.vocabulary_uri')</dt>
                                <dd> {!! getClickableLink($recipientRegionBudget['recipient_region'][0]['vocabulary_uri']) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.code')</dt>
                                <dd>{!! getCodeNameWithCodeValue('Region' , $recipientRegionBudget['recipient_region'][0]['code'] , -5) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.description')</dt>
                                <dd>
                                    {!! getFirstNarrative($recipientRegionBudget['recipient_region'][0]) !!}<br>
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientRegionBudget['recipient_region'][0]['narrative'])])
                                </dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.budget_line')</dt>
                                 @foreach($recipientRegionBudget['budget_line'] as $budgetLine)
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
    <a href="{{ url('/organization/' . $orgId . '/recipient-region-budget') }}" class="edit-element">edit</a>
@endif