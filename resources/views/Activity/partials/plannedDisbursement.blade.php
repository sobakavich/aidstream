@if(!emptyOrHasEmptyTemplate($plannedDisbursements))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.planned_disbursement')</div>
        @foreach( groupBudgetElements($plannedDisbursements , 'planned_disbursement_type') as $key => $disbursements)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('BudgetType' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($disbursements as $disbursement)
                        <li>{!! getCurrencyValueDate($disbursement['value'][0] , "planned") !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.period')</dt>
                                <dd>{!! getBudgetPeriod($disbursement) !!}</dd>
                            </dl>
                            @if(session('version') != 'V201')
                                <dl>
                                    <dt>@lang('activityView.provider_organization')</dt>
                                    <dd>
                                        {!!  getFirstNarrative($disbursement['provider_org'][0])  !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($disbursement['provider_org'][0]['narrative'])])
                                        {!! getDisbursementOrganizationDetails($disbursement , 'provider_org') !!}
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>@lang('activityView.receiver_organization')</dt>
                                    <dd>
                                        {!!  getFirstNarrative($disbursement['receiver_org'][0])  !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($disbursement['receiver_org'][0]['narrative'])])
                                        {!! getDisbursementOrganizationDetails($disbursement , 'receiver_org') !!}
                                    </dd>
                                </dl>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.planned-disbursement.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'planned_disbursement'])}}" class="delete pull-right">remove</a>
    </div>
@endif
