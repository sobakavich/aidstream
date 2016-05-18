@if(!emptyOrHasEmptyTemplate($locations))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.location')</dt>
                <dd>
                @foreach(getLocationReach($locations) as $key => $locations)
                    <dt>{{ $key }}</dt>
                    <dd>
                        @foreach($locations as $location)
                            <li>{!!  getFirstNarrative($location['name'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['name'][0]['narrative'])])
                            </li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.location_reference')
                                    : {!! checkIfEmpty($location['reference']) !!}
                                </dl>
                                <dl>@lang('activityView.location_id_vocabulary')
                                    : @foreach($location['location_id'] as $locationId)
                                        <li>{!!  getLocationIdVocabulary($locationId)  !!}</li>
                                    @endforeach
                                </dl>

                                <hr>
                                <dl>@lang('activityView.location_description')
                                    : {!! getFirstNarrative($location['location_description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['location_description'][0]['narrative'])])
                                </dl>
                                <dl>@lang('activityView.activity_description')
                                    : {!! getFirstNarrative($location['activity_description'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($location['activity_description'][0]['narrative'])])
                                </dl>

                                <dl>@lang('activityView.administrative_vocabulary')
                                    @foreach($location['administrative'] as $locationAdministrative)
                                        <li>{!! getAdministrativeVocabulary($locationAdministrative) !!}</li>
                                    @endforeach
                                </dl>

                                <dl>@lang('activityView.point')
                                    :{!! getLocationPoint($location) !!}
                                </dl>

                                <dl>@lang('activityView.exactness')
                                    :{!! getLocationPropertiesValues($location , 'exactness' ,'GeographicExactness' ) !!}
                                </dl>

                                <dl>@lang('activityView.location_class')
                                    :{!! getLocationPropertiesValues($location , 'location_class' ,'GeographicLocationClass' ) !!}
                                </dl>
                                <dl>@lang('activityView.feature_designation')
                                    :{!! getLocationPropertiesValues($location , 'feature_designation' ,'LocationType' , -6) !!}
                                </dl>

                            </dl>

                        @endforeach
                        <hr>
                        @endforeach
                    </dd>

            </dl>
            {{--<a href="{{route('activity.location.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'location'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
