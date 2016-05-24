@if(!emptyOrHasEmptyTemplate($documentLinks))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.document_link')</div>
            <div class="activity-element-info">
                @foreach($documentLinks as $documentLink)
                    <li>{!! getClickableLink($documentLink['document_link']['url']) !!}</li>
                    <div class="toggle-btn">
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                    </div>
                    <div class="more-info">
                        <dl>
                            <dt>@lang('activityView.title')</dt>
                            <dd>
                                {!! getFirstNarrative($documentLink['document_link']['title'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['document_link']['title'][0]['narrative'])])
                            </dd>

                        </dl>
                        <dl>
                            <dt>@lang('activityView.format')</dt>
                            <dd>{{ $documentLink['document_link']['format'] }}</dd>
                        </dl>
                        <dl>
                            <dt>@lang('activityView.category')</dt>
                            @foreach($documentLink['document_link']['category'] as $category)
                                <dd>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!}</dd>
                            @endforeach
                        </dl>
                        <dl>
                            <dt>@lang('activityView.language')</dt>
                            <dd>{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['document_link']['language'])) !!}</dd>
                        </dl>
                        @if(session('version') != 'V201')
                            <dl>
                                <dt>@lang('activityView.document_date')</dt>
                                <dd>{!! checkIfEmpty(formatDate(getVal($documentLink , ['document_link' , 'document_date' , 0 , 'date']))) !!}</dd>
                            </dl>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        {{--@if(!request()->route()->hasParameter('document_link'))--}}
        {{--<a href="{{route('activity.document-link.index', $id)}}" class="edit-element">edit</a>--}}
        {{--@endif--}}
    </div>
@endif
