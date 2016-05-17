@if(!emptyOrHasEmptyTemplate($transactions))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.transaction')</dt>
                <dd>
                @foreach(groupTransactionElements($transactions) as $key => $groupedTransactions)
                    <dd>
                        @foreach($groupedTransactions as $transaction)
                            <li>
                                {!! getCurrencyValueDate($transaction['value'][0] , "transaction") !!}
                            </li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.transaction_reference')
                                    : {{ $transaction['reference'] }}
                                </dl>

                                <dl>@lang('activityView.description')
                                    : {!! getFirstNarrative($transaction['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['description'][0]['narrative'])])
                                </dl>

                                <dl>@lang('activityView.provider_organization')
                                    : {!! getFirstNarrative($transaction['provider_organization'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['provider_organization'][0]['narrative'])])
                                    {!! getTransactionProviderDetails($transaction['provider_organization'][0] , 'provider') !!}
                                </dl>

                                <dl>@lang('activityView.receiver_organization')
                                    : {!! getFirstNarrative($transaction['receiver_organization'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['receiver_organization'][0]['narrative'])])
                                    {!! getTransactionProviderDetails($transaction['receiver_organization'][0] , 'receiver') !!}
                                </dl>

                                <dl>@lang('activityView.disbursement_channel')
                                    : {!! checkIfEmpty($getCode->getCodeNameOnly('DisbursementChannel' , $transaction['disbursement_channel'][0]['disbursement_channel_code'])) !!}
                                </dl>

                                <dl>@lang('activityView.sector')
                                    : {!! checkIfEmpty($getCode->getCodeNameOnly('SectorCategory' , $transaction['sector'][0]['sector_category_code'] , -5)) !!}
                                    {!! getTransactionSectorDetails($transaction['sector'][0]) !!} <br>
                                    {!! getFirstNarrative($transaction['sector'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['sector'][0]['narrative'])])
                                </dl>

                                <dl>@lang('activityView.recipient_country')
                                    : {!! getCountryNameWithCode($transaction['recipient_country'][0]['country_code']) !!}
                                    <br>
                                    @if(!empty($transaction['recipient_country'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative($transaction['recipient_country'][0]) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['recipient_country'][0]['narrative'])])
                                    @endif
                                </dl>

                                <dl>@lang('activityView.recipient_region')
                                    : {!! getCodeNameWithCodeValue('Region' , $transaction['recipient_region'][0]['region_code'] , -4) !!}
                                    <br>
                                    {!! getRecipientRegionDetails($transaction['recipient_region'][0]) !!} <br> <br>
                                    @if(!empty($transaction['recipient_region'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative($transaction['recipient_region'][0]) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['recipient_region'][0]['narrative'])])
                                    @endif
                                </dl>

                                <dl>@lang('activityView.flow_type')
                                    : {!! checkIfEmpty(getCodeNameWithCodeValue('FlowType' , $transaction['flow_type'][0]['flow_type'] , -4)) !!}
                                </dl>

                                <dl>@lang('activityView.finance_type')
                                    : {!! checkIfEmpty(getCodeNameWithCodeValue('FinanceType' , $transaction['finance_type'][0]['finance_type'] , -5)) !!}
                                </dl>

                                <dl>@lang('activityView.aid_type')
                                    : {!! checkIfEmpty(getCodeNameWithCodeValue('AidType' , $transaction['aid_type'][0]['aid_type'] , -5)) !!}
                                </dl>

                                <dl>@lang('activityView.tied_status')
                                    : {!! checkIfEmpty(getCodeNameWithCodeValue('TiedStatus' , $transaction['tied_status'][0]['tied_status_code'] , -4)) !!}
                                </dl>
                            </dl>
                        @endforeach

                        <hr>
                        @endforeach
                    </dd>
            </dl>
            {{--<a href="{{route('activity.transaction.index', $id)}}" class="edit-element">edit</a>--}}
        </div>
    </div>
@endif
