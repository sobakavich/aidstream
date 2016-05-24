@if(!emptyOrHasEmptyTemplate($transactions))

    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.transaction')</div>
        @foreach(groupTransactionElements($transactions) as $key => $groupedTransactions)
            @foreach($groupedTransactions as $transaction)
                <div class="activity-element-list">
                    <div class="activity-element-label">{{$getCode->getCodeNameOnly('TransactionType' , $transaction['transaction_type'][0]['transaction_type_code'] )}}</div>
                    <div class="activity-element-info">
                        <li>{!! getCurrencyValueDate($transaction['value'][0] , "transaction") !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.transaction_reference')</dt>
                                <dd>{!! checkIfEmpty($transaction['reference']) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.description')</dt>
                                <dd>
                                    {!! getFirstNarrative($transaction['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['description'][0]['narrative'])])
                                </dd>
                            </dl>
                            @if(session('version') != 'V201')
                                @if(array_key_exists('humanitarian' , $transaction))
                                    <dl>
                                        <dt>@lang('activityView.humanitarian')</dt>
                                        @if($transaction['humanitarian'] == "")
                                            <dd><em>Not Available</em></dd>
                                        @elseif($transaction['humanitarian'] == 1)
                                            <dd>Yes</dd>
                                        @elseif($transaction['humanitarian'] == 0)
                                            <dd>No</dd>
                                        @endif
                                    </dl>
                                @endif
                            @endif
                            <dl>
                                <dt>@lang('activityView.transaction_type')</dt>
                                <dd>{!! getCodeNameWithCodeValue('TransactionType' , $transaction['transaction_type'][0]['transaction_type_code'] , -4) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.transaction_date')</dt>
                                <dd>{{ formatDate($transaction['transaction_date'][0]['date']) }}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.provider_organization')</dt>
                                <dd>
                                    {!! getFirstNarrative($transaction['provider_organization'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['provider_organization'][0]['narrative'])])
                                    {!! getTransactionProviderDetails($transaction['provider_organization'][0] , 'provider') !!}
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.receiver_organization')</dt>
                                <dd>
                                    {!! getFirstNarrative($transaction['receiver_organization'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['receiver_organization'][0]['narrative'])])
                                    {!! getTransactionProviderDetails($transaction['receiver_organization'][0] , 'receiver') !!}
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.disbursement_channel')</dt>
                                <dd>{!! checkIfEmpty($getCode->getCodeNameOnly('DisbursementChannel' , $transaction['disbursement_channel'][0]['disbursement_channel_code'])) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.sector')</dt>
                                <dd>
                                    {!! getSectorInformation($transaction['sector'][0] , "") !!}
                                    {!! getTransactionSectorDetails($transaction['sector'][0]) !!} <br>
                                    {!! getFirstNarrative($transaction['sector'][0]) !!}
                                </dd>
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['sector'][0]['narrative'])])
                            </dl>

                            <dl>
                                <dt>@lang('activityView.recipient_country')</dt>
                                <dd>
                                    {!! getCountryNameWithCode($transaction['recipient_country'][0]['country_code']) !!}
                                    <br>
                                    @if(!empty($transaction['recipient_country'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative($transaction['recipient_country'][0]) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['recipient_country'][0]['narrative'])])
                                    @endif
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.recipient_region')</dt>
                                <dd>
                                    {!! getCodeNameWithCodeValue('Region' , $transaction['recipient_region'][0]['region_code'] , -4) !!}
                                    <br>
                                    {!! getRecipientRegionDetails($transaction['recipient_region'][0]) !!} <br> <br>
                                    @if(!empty($transaction['recipient_region'][0]['narrative'][0]['narrative']))
                                        {!! getFirstNarrative($transaction['recipient_region'][0]) !!}
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($transaction['recipient_region'][0]['narrative'])])
                                    @endif
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.flow_type')</dt>
                                <dd>{!! checkIfEmpty(getCodeNameWithCodeValue('FlowType' , $transaction['flow_type'][0]['flow_type'] , -4)) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.finance_type')</dt>
                                <dd>{!! checkIfEmpty(getCodeNameWithCodeValue('FinanceType' , $transaction['finance_type'][0]['finance_type'] , -5)) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.aid_type')</dt>
                                <dd>{!! checkIfEmpty(getCodeNameWithCodeValue('AidType' , $transaction['aid_type'][0]['aid_type'] , -5)) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.tied_status')</dt>
                                <dd>{!! checkIfEmpty(getCodeNameWithCodeValue('TiedStatus' , $transaction['tied_status'][0]['tied_status_code'] , -4)) !!}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
        <a href="{{route('activity.transaction.index', $id)}}" class="edit-element">edit</a>
    </div>
@endif
