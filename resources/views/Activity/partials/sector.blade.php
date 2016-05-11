@if(!emptyOrHasEmptyTemplate($sectors))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.sector')</dt>
                <dd>
                @foreach(groupActivityElements($sectors , 'sector_vocabulary') as $key => $sectors)
                    <dt>{{ $getCode->getCodeNameOnly('SectorVocabulary' , $key) }}</dt>
                    <dd>
                        @foreach($sectors as $sector)
                            <li>{!! checkIfEmpty(getSectorInformation($sector)) . ' ( '.$sector['percentage'].'%)'  !!} </li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.vocabulary_uri')
                                    :{!!  checkIfEmpty(getClickableLink($sector['vocabulary_uri']))  !!}
                                </dl>

                                <dl>@lang('activityView.description')
                                    : {!!  getFirstNarrative($sector)  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($sector['narrative'])])
                                </dl>
                            </dl>

                        @endforeach
                        <hr>

                        @endforeach
                    </dd>
            {{--<a href="{{route('activity.sector.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'sector'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
