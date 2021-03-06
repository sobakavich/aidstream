@if(!empty($humanitarianScopes))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.humanitarian_scope')</div>
        @foreach(groupActivityElements($humanitarianScopes , 'type' ) as $key => $humanitarianScopes)
            <div class="activity-element-list">
                <div class="activity-element-label"> {{ $getCode->getCodeNameOnly('HumanitarianScopeType' , $key) }} </div>
                <div class="activity-element-info">
                    @foreach($humanitarianScopes as $humanitarianScope)
                        <li>
                            {!! checkIfEmpty(getFirstNarrative($humanitarianScope)) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($humanitarianScope['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.vocabulary')</div>
                                <div class="activity-element-info">{{ getCodeNameWithCodeValue('HumanitarianScopeVocabulary' , $humanitarianScope['vocabulary'] , -5) }}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.vocabulary_uri')</div>
                                <div class="activity-element-info">{!! getClickableLink($humanitarianScope['vocabulary_uri']) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.code')</div>
                                <div class="activity-element-info">{{ checkIfEmpty($humanitarianScope['code']) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.humanitarian-scope.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'humanitarian_scope'])}}" class="delete pull-right">remove</a>
    </div>
@endif
