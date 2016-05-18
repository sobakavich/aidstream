@if(!emptyOrHasEmptyTemplate($results))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.results')</dt>
                <dd>
                @foreach(groupResultElements($results) as $key => $results)
                    <dt>{{ $key }}</dt>
                    <dd>
                        @foreach($results as $result)
                            <li> {!! getFirstNarrative($result['title'][0]) !!} </li>
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['title'][0]['narrative'])])
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.description')
                                    : {!! getFirstNarrative($result['description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($result['description'][0]['narrative'])])
                                </dl>

                                <dl>@lang('activityView.aggregation_status')
                                    : @if($result['aggregation_status'] == 1)
                                        True
                                    @else
                                        False
                                    @endif
                                </dl>
                                <dl>@lang('activityView.indicators')
                                    :@foreach($result['indicator'] as $indicator)
                                        <dl><strong>{!! getFirstNarrative($indicator['title'][0]) !!}</strong>
                                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($indicator['title'][0]['narrative'])])
                                        </dl>
                                        @if(session('version') != 'V201')
                                            <dl>@lang('activityView.indicator_reference')
                                                :@if(array_key_exists('reference' , $indicator))
                                                    @foreach($indicator['reference'] as $reference)
                                                        <li>{!! getIndicatorReference($reference) !!}</li>
                                                    @endforeach
                                                @endif
                                            </dl>
                                        @endif
                                        <hr>
                                        <dl>@lang('activityView.measure')
                                            : {!! $getCode->getCodeNameOnly('IndicatorMeasure',$indicator['measure']) !!}
                                        </dl>
                                        <dl>@lang('activityView.baseline_value')
                                            : {!! getResultsBaseLine($indicator['measure'] , $indicator['baseline'][0]) !!}
                                            <br>
                                            {!! getFirstNarrative($indicator['baseline'][0]['comment'][0]) !!}
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
                        <hr>
                        @endforeach
                    </dd>
            </dl>
            {{--<a href="{{route('activity.result.index', $id)}}" class="edit-element">edit</a>--}}
        </div>
    </div>
@endif
