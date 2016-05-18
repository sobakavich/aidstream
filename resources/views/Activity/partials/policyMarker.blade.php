@if(!emptyOrHasEmptyTemplate($policyMarkers))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.policy_marker')</dt>
                <dd>
                @foreach(groupActivityElements($policyMarkers , 'vocabulary') as $key => $policyMarkers)

                    <dt>{{ $getCode->getCodeNameOnly('PolicyMarkerVocabulary' , $key) }}</dt>
                    @foreach($policyMarkers as $policyMarker)
                        <dd>
                            <li>{{ $policyMarker['policy_marker'] .' - '. $getCode->getCodeNameOnly('PolicyMarker' , $policyMarker['policy_marker']) }}</li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="hidden-info">
                                @if(session('version') == 'V201')
                                    <dl>@lang('activityView.vocabulary_uri')
                                        :{!! getClickableLink(getVal($policyMarker , ['vocabulary_uri'])) !!}
                                    </dl>
                                @endif
                                <dl>@lang('activityView.significance')
                                    : {!! getCodeNameWithCodeValue('PolicySignificance' , $policyMarker['significance'] , -4) !!}
                                </dl>

                                <dl>@lang('activityView.description')
                                    :{!! getFirstNarrative($policyMarker) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($policyMarker['narrative'])])
                                </dl>
                            </dl>
                        </dd>
                    @endforeach
                    <hr>
                    <dd>
                        @endforeach

                    </dd>
            {{--<a href="{{route('activity.policy-marker.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'policy_marker'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
