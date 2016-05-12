@if(!emptyOrHasEmptyTemplate($recipientRegions))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.recipient_region')</dt>
                <dd>
                    @foreach($recipientRegions as $recipientRegion)
                        <li>{!! getRecipientInformation($recipientRegion['region_code'], $recipientRegion['percentage'], 'Region') !!}</li>
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                        <dl class="hidden-info">
                            <dl>@lang('activityView.region_vocabulary')
                                :{{ $recipientRegion['region_vocabulary'] . '-' . substr($getCode->getActivityCodeName('RegionVocabulary', $recipientRegion['region_vocabulary']) , 0 , -4) }}
                            </dl>
                            <dl>@lang('activityView.vocabulary_uri')
                                :{!!  getClickableLink($recipientRegion['vocabulary_uri']) !!}
                            </dl>
                            <dl>@lang('activityView.description')
                                :{!! getFirstNarrative($recipientRegion) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientRegion['narrative'])])
                            </dl>
                        </dl>
                    @endforeach
                </dd>
            {{--<a href="{{route('activity.recipient-region.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'recipient_region'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
