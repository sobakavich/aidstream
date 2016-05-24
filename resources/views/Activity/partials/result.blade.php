@if(!emptyOrHasEmptyTemplate($results))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.results')</div>
        @foreach(groupResultElements($results) as $key => $results)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $key }}</div>
                <div class="activity-element-info">
                    @foreach($results as $result)
                        <li>
                            {!! getFirstNarrative($result['title'][0]) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['title'][0]['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.description')</dt>
                                <dd>
                                    {!! getFirstNarrative($result['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['description'][0]['narrative'])])
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.aggregation_status')</dt>
                                @if($result['aggregation_status'] == 1)
                                    <dd>True</dd>
                                @else
                                    <dd>False</dd>
                                @endif
                            </dl>
                            <dl>
                                <dt>@lang('activityView.indicators')</dt>
                                @foreach($result['indicator'] as $indicator)
                                    <dd><strong>{!! getFirstNarrative($indicator['title'][0]) !!}</strong>
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($indicator['title'][0]['narrative'])])
                                    </dd>
                                    @if(session('version') != 'V201')
                                        <dd>
                                            @lang('activityView.indicator_reference')
                                            @if(array_key_exists('reference' , $indicator))
                                                @foreach($indicator['reference'] as $reference)
                                                    <li>{!! getIndicatorReference($reference) !!}</li>
                                                @endforeach
                                            @endif
                                        </dd>
                                    @endif
                                    <dl>
                                        <dt>@lang('activityView.measure')</dt>
                                        <dd>{!! $getCode->getCodeNameOnly('IndicatorMeasure',$indicator['measure']) !!}</dd>
                                    </dl>
                                    <dl>
                                        <dt>@lang('activityView.ascending')</dt>
                                        @if($indicator['ascending'] == 1)
                                            <dd>Yes</dd>
                                        @elseif($indicator['ascending'] == 0)
                                            <dd>No</dd>
                                        @else
                                            <dd><em>Not Available</em></dd>
                                        @endif
                                    </dl>
                                    <dl>
                                        <dt>@lang('activityView.baseline_value')</dt>
                                        <dd>{!! getResultsBaseLine($indicator['measure'] , $indicator['baseline'][0]) !!}</dd>
                                        <dd>{!! getFirstNarrative($indicator['baseline'][0]['comment'][0]) !!}</dd>
                                    </dl>
                                    <dl>
                                        <span style="margin-right: 160px">Period</span> <span
                                                style="margin-right: 40px"> Target Value </span>
                                        <span> Actual Vaule </span>
                                        : @foreach(getIndicatorPeriod($indicator['measure'] , $indicator['period']) as $period)
                                            <br>

                                            <span style="margin-right: 40px">{!!   $period['period'] !!}</span>
                                            <a href="#"
                                               style="margin-right: 40px" data-toggle="tooltip"
                                               title="So i will add here">
                                                {!!  $period['target_value'] !!}
                                            </a>
                                            {{ dump(getTargetAdditionalDetails('target' , $period['target'])) }}


                                            <a href="#"> {!!  $period['actual_value']  !!} </a>
                                            {{ dump(getTargetAdditionalDetails('actual' , $period['actual'])) }}
                                            <br>
                                        @endforeach
                                        <hr>
                                    </dl>
                                @endforeach
                            </dl>
                            </dl>
                            <hr>

                            @endforeach
                        </div>
                </div>
                @endforeach
            </div>
@endif
