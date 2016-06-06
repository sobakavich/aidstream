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
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($result['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['description'][0]['narrative'])])
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.aggregation_status')</div>
                                @if($result['aggregation_status'] == 1)
                                    <div class="activity-element-info">True</div>
                                @else
                                    <div class="activity-element-info">False</div>
                                @endif
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.indicators')</div>
                                @foreach($result['indicator'] as $indicator)
                                    <div class="activity-element-info">
                                        <strong>{!! getFirstNarrative($indicator['title'][0]) !!}</strong>
                                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($indicator['title'][0]['narrative'])])
                                    </div>
                                    @if(session('version') != 'V201')
                                        <div class="activity-element-info">
                                            @lang('activityView.indicator_reference')
                                            @if(array_key_exists('reference' , $indicator))
                                                @foreach($indicator['reference'] as $reference)
                                                    <li>{!! getIndicatorReference($reference) !!}</li>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                    <div class="element-info">
                                        <div class="activity-element-label">@lang('activityView.measure')</div>
                                        <div class="activity-element-info">{!! $getCode->getCodeNameOnly('IndicatorMeasure',$indicator['measure']) !!}</div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">@lang('activityView.ascending')</div>
                                        @if($indicator['ascending'] == 1)
                                            <div class="activity-element-info">Yes</div>
                                        @elseif($indicator['ascending'] == 0)
                                            <div class="activity-element-info">No</div>
                                        @else
                                            <div class="activity-element-info"><em>Not Available</em></div>
                                        @endif
                                        <hr>
                                        <div class="element-info">@lang('activityView.baseline_value')
                                            : {!! getResultsBaseLine($indicator['measure'] , $indicator['baseline'][0]) !!}
                                            <br>
                                            {!! getFirstNarrative($indicator['baseline'][0]['comment'][0]) !!}
                                        </div>
                                        <div class="element-info">
                                            <span style="margin-right: 160px">Period</span> <span
                                                    style="margin-right: 40px"> Target Value </span>
                                            <span> Actual Vaule </span>
                                            : @foreach(getIndicatorPeriod($indicator['measure'] , $indicator['period']) as $period)
                                                <br>

                                                <span style="margin-right: 40px">{!!   $period['period'] !!}</span>
                                                <a href="#"
                                                   style="margin-right: 40px" data-toggle="tooltip"
                                                   title="So i will add here" class="show-more-info">
                                                    {!!  $period['target_value'] !!}
                                                </a>
                                                @include('Activity.partials.resultPartials.target' , ['type' => 'target'])
                                                <br>

                                                <a href="#"
                                                   class="show-more-info"> {!!  checkIfEmpty($period['actual_value'])  !!} </a>
                                                @include('Activity.partials.resultPartials.target' , ['type' => 'actual'])
                                            @endforeach
                                            <hr>
                                        </div>
                                        @endforeach
                                    </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.result.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'result'])}}" class="delete pull-right">remove</a>
    </div>
@endif
