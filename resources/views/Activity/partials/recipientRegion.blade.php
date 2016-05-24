@if(!emptyOrHasEmptyTemplate($recipientRegions))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.recipient_region')</div>
            <div class="activity-element-info">
                @foreach($recipientRegions as $recipientRegion)
                    <li>{!! getRecipientInformation($recipientRegion['region_code'], $recipientRegion['percentage'], 'Region') !!}</li>
                    <div class="toggle-btn">
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                    </div>
                    <div class="more-info">
                        <dl>
                            <dt>@lang('activityView.region_vocabulary')</dt>
                            <dd>{{ $recipientRegion['region_vocabulary'] . '-' . substr($getCode->getActivityCodeName('RegionVocabulary', $recipientRegion['region_vocabulary']) , 0 , -4) }}</dd>
                        </dl>
                        @if(session('version') != 'V201')
                            <dl>
                                <dt>@lang('activityView.vocabulary_uri')</dt>
                                <dd>{!!  getClickableLink($recipientRegion['vocabulary_uri']) !!}</dd>
                            </dl>
                        @endif
                        <dl>
                            <dt>@lang('activityView.description')</dt>
                            <dd>
                                {!! getFirstNarrative($recipientRegion) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientRegion['narrative'])])
                            </dd>
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>
        {{--<a href="{{route('activity.recipient-region.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'recipient_region'])}}" class="delete pull-right">remove</a>--}}
    </div>
@endif
