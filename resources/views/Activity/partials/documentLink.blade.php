@if(!emptyOrHasEmptyTemplate($documentLinks))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.document_link')</dt>
                <dd>
                    @foreach($documentLinks as $documentLink)
                        <li>{!! getClickableLink($documentLink['document_link']['url']) !!}</li>
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                        <dl class="more-info-hidden">
                            <dl>@lang('activityView.title')
                                : {!! getFirstNarrative($documentLink['document_link']['title'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['document_link']['title'][0]['narrative'])])
                            </dl>
                            <dl>@lang('activityView.format')
                                :{{ $documentLink['document_link']['format'] }}
                            </dl>
                            <dl>@lang('activityView.category')
                                @foreach($documentLink['document_link']['category'] as $category)
                                    <li>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!}</li>
                                @endforeach
                            </dl>
                        </dl>
                    @endforeach

                </dd>
            </dl>
            {{--@if(!request()->route()->hasParameter('document_link'))--}}
                {{--<a href="{{route('activity.document-link.index', $id)}}" class="edit-element">edit</a>--}}
            {{--@endif--}}
        </div>
    </div>
@endif
