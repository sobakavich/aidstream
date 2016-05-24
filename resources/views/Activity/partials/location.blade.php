@if(!emptyOrHasEmptyTemplate($locations))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.location')</div>
        @foreach(getLocationReach($locations) as $key => $locations)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $key }}
                </div>
                <div class="activity-element-info">
                    @foreach($locations as $location)
                        <li>
                            {!!  getFirstNarrative($location['name'][0]) !!}
                            @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['name'][0]['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.location_reference')</dt>
                                <dd>{!! checkIfEmpty($location['reference']) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.location_id_vocabulary')</dt>
                                <dd>
                                    @foreach($location['location_id'] as $locationId)
                                        <li>{!!  getLocationIdVocabulary($locationId)  !!}</li>
                                    @endforeach
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.location_description')</dt>
                                <dd>
                                    {!! getFirstNarrative($location['location_description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['location_description'][0]['narrative'])])
                                </dd>

                            </dl>
                            <dl>
                                <dt>@lang('activityView.activity_description')</dt>
                                <dd>
                                    {!! getFirstNarrative($location['activity_description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['activity_description'][0]['narrative'])])
                                </dd>

                            </dl>
                            <dl>
                                <dt> @lang('activityView.administrative_vocabulary')</dt>
                                <dd>
                                    @foreach($location['administrative'] as $locationAdministrative)
                                        <li>{!! getAdministrativeVocabulary($locationAdministrative) !!}</li>
                                    @endforeach
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.point')</dt>
                                <dd>{!! getLocationPoint($location) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.exactness')</dt>
                                <dd>{!! getLocationPropertiesValues($location , 'exactness' ,'GeographicExactness' ) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.location_class')</dt>
                                <dd>{!! getLocationPropertiesValues($location , 'location_class' ,'GeographicLocationClass' ) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.feature_designation')</dt>
                                <dd>{!! getLocationPropertiesValues($location , 'feature_designation' ,'LocationType' , -6) !!}</dd>
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.location.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'location'])}}" class="delete pull-right">remove</a>
    </div>
@endif
