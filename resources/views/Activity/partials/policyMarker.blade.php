@if(!emptyOrHasEmptyTemplate($policyMarkers))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.policy_marker')</div>
        @foreach(groupActivityElements($policyMarkers , 'vocabulary') as $key => $policyMarkers)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('PolicyMarkerVocabulary' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($policyMarkers as $policyMarker)
                        <li>{{ $policyMarker['policy_marker'] .' - '. $getCode->getCodeNameOnly('PolicyMarker' , $policyMarker['policy_marker']) }}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            @if(session('version') == 'V201')
                                <dl>
                                    <dt>@lang('activityView.vocabulary_uri')</dt>
                                    <dd>{!! getClickableLink(getVal($policyMarker , ['vocabulary_uri'])) !!}</dd>
                                </dl>
                            @endif
                            <dl>
                                <dt>@lang('activityView.significance')</dt>
                                <dd>{!! getCodeNameWithCodeValue('PolicySignificance' , $policyMarker['significance'] , -4) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.description')</dt>
                                <dd>
                                    {!! getFirstNarrative($policyMarker) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($policyMarker['narrative'])])
                                </dd>
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        {{--<a href="{{route('activity.policy-marker.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'policy_marker'])}}" class="delete pull-right">remove</a>--}}
    </div>
@endif
