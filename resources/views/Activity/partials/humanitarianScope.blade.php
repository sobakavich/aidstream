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
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <dl class="more-info">
                            <dl>
                                <dt>@lang('activityView.vocabulary')</dt>
                                <dd>{{ getCodeNameWithCodeValue('HumanitarianScopeVocabulary' , $humanitarianScope['vocabulary'] , -5) }}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.vocabulary_uri')</dt>
                                <dd>{!! getClickableLink($humanitarianScope['vocabulary_uri']) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.code')</dt>
                                <dd>{{ checkIfEmpty($humanitarianScope['code']) }}</dd>
                            </dl>
                        </dl>
                    @endforeach
                </div>
            </div>
        @endforeach
        {{--<a href="{{route('activity.humanitarian-scope.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'humanitarian_scope'])}}" class="delete pull-right">remove</a>--}}
    </div>
@endif
