@if(!emptyOrHasEmptyTemplate($sectors))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.sector')</div>
        @foreach(groupActivityElements($sectors , 'sector_vocabulary') as $key => $sectors)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('SectorVocabulary' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($sectors as $sector)
                        <li>{!! checkIfEmpty(getSectorInformation($sector , $sector['percentage']))  !!}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                @if(session('version') != 'V201')
                                    <dt>@lang('activityView.vocabulary_uri')</dt>
                                    <dd>{!!  checkIfEmpty(getClickableLink($sector['vocabulary_uri']))  !!}</dd>
                                @endif
                            </dl>
                            <dl>
                                <dt>@lang('activityView.description')</dt>
                                <dd>
                                    {!!  getFirstNarrative($sector)  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($sector['narrative'])])
                                </dd>
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        {{--<a href="{{route('activity.sector.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'sector'])}}" class="delete pull-right">remove</a>--}}
    </div>
@endif
