@if(!emptyOrHasEmptyTemplate($results))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.results')</dt>
                <dd>
                @foreach(groupResultElements($results) as $key => $results)
                    <dt>{{ $key }}</dt>
                    <dd>
                        {{ dump($results) }}
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
                                        <dl>@lang('activityView.indicator_reference')
                                            : @foreach($indicator['reference'] as $reference)
                                                <li>{!! getIndicatorReference($reference) !!}</li>
                                            @endforeach
                                        </dl>
                                        <hr>
                                        <dl>@lang('activityView.baseline_value')
                                            :{!! getResultsBaseLine($indicator['baseline'][0]) !!}
                                            <br>
                                            {!! getFirstNarrative($indicator['baseline'][0]['comment'][0]) !!}
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
        {{--<div class="panel-body panel-level-1">--}}
        {{--@foreach($results as $result)--}}
        {{--<div class="panel-heading">--}}
        {{--<div class="activity-element-title">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>--}}
        {{--</div>--}}
        {{--<div class="panel-body">--}}
        {{--<div class="panel panel-default">--}}
        {{--<div class="panel-element-body row">--}}
        {{--<div class="col-xs-12 col-md-12">--}}
        {{--<div class="col-xs-12 col-sm-4">Type:</div>--}}
        {{--<div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>--}}
        {{--</div>--}}
        {{--<div class="col-xs-12 col-md-12">--}}
        {{--<div class="col-xs-12 col-sm-4">Aggregation Status:</div>--}}
        {{--<div class="col-xs-12 col-sm-8">{{($result['result']['aggregation_status'] == "1") ? 'True' : 'False' }}</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">--}}
        {{--@include('Activity.partials.resultPartials.title')--}}
        {{--@include('Activity.partials.resultPartials.description')--}}


        {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">--}}
        {{--<div class="activity-element-title">Indicator</div>--}}
        {{--</div>--}}
        {{--<div class="panel-level-body">--}}
        {{--@foreach($result['result']['indicator'] as $indicator)--}}
        {{--<div class="panel-heading">--}}
        {{--<div class="activity-element-title">{{$getCode->getActivityCodeName('IndicatorMeasure', $indicator['measure'])}}</div>--}}
        {{--</div>--}}
        {{--<div class="panel-body">--}}
        {{--<div class="panel-element-body">--}}
        {{--<div class="col-xs-12 col-md-12">--}}
        {{--<div class="col-xs-12 col-sm-4">Measure:</div>--}}
        {{--<div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('IndicatorMeasure', $indicator['measure'])}}</div>--}}
        {{--</div>--}}
        {{--<div class="col-xs-12 col-md-12">--}}
        {{--<div class="col-xs-12 col-sm-4">Ascending:</div>--}}
        {{--<div class="col-xs-12 col-sm-8">{{($indicator['ascending'] == "1") ? 'True' : 'False' }}</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-xs-12 col-md-12 col-lg-12 panel-level-3">--}}
        {{--@include('Activity.partials.resultPartials.indicatorTitle')--}}
        {{--@include('Activity.partials.resultPartials.indicatorDescription')--}}
        {{--@include('Activity.partials.resultPartials.indicatorBaseline')--}}

        {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">--}}
        {{--<div class="activity-element-title">Period</div>--}}
        {{--</div>--}}
        {{--<div class="panel-sub-body panel-level-4">--}}
        {{--@foreach($indicator['period'] as $period)--}}
        {{--@include('Activity.partials.resultPartials.indicatorPeriodStart')--}}
        {{--@include('Activity.partials.resultPartials.indicatorPeriodEnd')--}}
        {{--@include('Activity.partials.resultPartials.indicatorTarget')--}}
        {{--@include('Activity.partials.resultPartials.indicatorActual')--}}
        {{--@endforeach--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endforeach--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endforeach--}}
        {{--</div>--}}
    </div>
@endif
