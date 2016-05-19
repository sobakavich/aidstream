@if(!emptyOrHasEmptyTemplate($recipient_organization_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.recipient_organization_budget')</div>
        @foreach(groupActivityElements($recipient_organization_budget , 'status') as  $key => $recipientOrganizationBudgets)

            <div class="activity-element-list">
                <div class="activity-element-label">
                    @if(session('version') != 'V201')
                        {{ $getCode->getCodeNameOnly('BudgetStatus' , $key) }}
                        @else
                        Status Not Available
                    @endif
                </div>
                <div class="activity-element-info">
                    @foreach($recipientOrganizationBudgets as $recipientOrganizationBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $recipientOrganizationBudget) !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.period'):</dt>
                                <dd>{!! checkIfEmpty(getBudgetInformation('period' , $recipientOrganizationBudget)) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.recipient_organization_reference'):</dt>
                                <dd>
                                    {!! checkIfEmpty($recipientOrganizationBudget['recipient_organization'][0]['ref']) !!}
                                </dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.description'):</dt>
                                <dd>
                                    {!! checkIfEmpty(getFirstNarrative($recipientOrganizationBudget['recipient_organization'][0])) !!}
                                </dd>
                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($recipientOrganizationBudget['recipient_organization'][0]['narrative'])])
                            </dl>

                            {{--Sweta didi yo milaunu hai--}}
                            {{--=============================================--}}

                            {{--<dl>--}}
                            {{--<dt>@lang('activityView.budget_line')</dt>--}}

                            {{--@foreach($recipientOrganizationBudget['budget_line'] as $budgetLine)--}}
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

                            {{--=========================================================--}}
                            {{--Ending here--}}
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
