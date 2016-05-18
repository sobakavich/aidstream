@if(!empty($humanitarianScopes))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.humanitarian_scope')</dt>
                <dd>
                @foreach(groupActivityElements($humanitarianScopes , 'type' ) as $key => $humanitarianScopes)
                    <dt> {{ $getCode->getCodeNameOnly('HumanitarianScopeType' , $key) }} </dt>
                    <dd>
                        @foreach($humanitarianScopes as $humanitarianScope)
                            <li>
                                {!! checkIfEmpty(getFirstNarrative($humanitarianScope)) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($humanitarianScope['narrative'])])
                            </li>

                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="hidden-info">
                                <dl>@lang('activityView.vocabulary')
                                    :{{ getCodeNameWithCodeValue('HumanitarianScopeVocabulary' , $humanitarianScope['vocabulary'] , -5) }}
                                </dl>

                                <dl>@lang('activityView.vocabulary_uri')
                                    : {!! getClickableLink($humanitarianScope['vocabulary_uri']) !!}
                                </dl>

                                <dl>@lang('activityView.code')
                                    : {{ checkIfEmpty($humanitarianScope['code']) }}
                                </dl>
                            </dl>
                        @endforeach
                        <hr>
                        @endforeach

                    </dd>
            </dl>

        </div>
    </div>

    {{--<a href="{{route('activity.humanitarian-scope.index', $id)}}" class="edit-element">edit</a>--}}
    {{--<a href="{{route('activity.delete-element', [$id, 'humanitarian_scope'])}}" class="delete pull-right">remove</a>--}}

@endif
