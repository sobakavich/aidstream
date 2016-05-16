@if(!emptyOrHasEmptyTemplate($plannedDisbursements))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.planned_disbursement')</dt>
                <dd>
                @foreach( groupBudgetElements($plannedDisbursements , 'planned_disbursement_type') as $key => $disbursements)
                    <dt>{{ $getCode->getCodeNameOnly('BudgetType' , $key) }}</dt>
                    <dd>
                        @foreach($disbursements as $disbursement)
                            <li>{!! getCurrencyValueDate($disbursement['value'][0] , "planned") !!}</li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.period')
                                    :{!! getBudgetPeriod($disbursement) !!}
                                </dl>

                                <dl>@lang('activityView.provider_organization')
                                    :{!!  getFirstNarrative($disbursement['provider_org'][0])  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($disbursement['provider_org'][0]['narrative'])])
                                    {!! getDisbursementOrganizationDetails($disbursement , 'provider_org') !!}
                                </dl>

                                <dl>@lang('activityView.receiver_organization')
                                    :{!!  getFirstNarrative($disbursement['receiver_org'][0])  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($disbursement['receiver_org'][0]['narrative'])])
                                    {!! getDisbursementOrganizationDetails($disbursement , 'receiver_org') !!}
                                </dl>
                            </dl>
                        @endforeach
                        <hr>
                        @endforeach
                    </dd>
            </dl>
            {{--<a href="{{route('activity.planned-disbursement.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'planned_disbursement'])}}" class="delete pull-right">remove</a>--}}
        </div>

    </div>
@endif
