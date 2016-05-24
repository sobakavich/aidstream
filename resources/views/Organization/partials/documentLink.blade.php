@if(!emptyOrHasEmptyTemplate($document_link))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.document_link')</div>
            <div class="activity-element-info">
                @foreach($document_link as $documentLink)
                    <li>{!! getClickableLink($documentLink['url']) !!}</li>
                    <div class="toggle-btn">
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                    </div>
                    <div class="more-info">
                        <dl>
                            <dt>@lang('activityView.title')</dt>
                            <dd>
                                {!! getFirstNarrative($documentLink) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($documentLink['narrative'])])
                            </dd>
                        </dl>
                        <dl>
                            <dt>@lang('activityView.category')</dt>
                            <dd>
                                @foreach($documentLink['category'] as $category)
                                    <li>{!! getCodeNameWithCodeValue('DocumentCategory' , $category['code'] , -5) !!}</li>
                                @endforeach
                            </dd>
                        </dl>
                        <dl>
                            <dt>@lang('activityView.language')</dt>
                            <dd>{!! checkIfEmpty(getDocumentLinkLanguages($documentLink['language'])) !!}</dd>
                        </dl>
                        <dl>
                            <dt>@lang('activityView.document_date')</dt>
                            <dd>{!! checkIfEmpty(formatDate(getVal($documentLink , ['document_date' , 0 , 'date']))) !!}</dd>
                        </dl>
                        <dl>
                            <dt>@lang('activityView.recipient_country')</dt>
                            @foreach($documentLink['recipient_country'] as $country)
                                <dd>
                                    <li>{!! getCountryNameWithCode($country['code']) !!}</li>
                                    <dd>
                                        @lang('activityView.narrative'): {!! checkIfEmpty(getFirstNarrative($country)) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($country['narrative'])])
                                    </dd>
                                </dd>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <a href="{{ url('/organization/' . $orgId . '/document-link') }}" class="edit-element">edit</a>
@endif
