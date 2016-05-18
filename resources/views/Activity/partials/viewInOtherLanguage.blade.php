@if(! empty($otherLanguages))
    <a href="#" class="view-other-language">
        @if(count($otherLanguages) == 1 && empty($otherLanguages[0]['narrative']))
        @else
            <em>@lang('activityView.view_in_other_languages')</em>
        @endif

        <div class="hidden">
            @foreach($otherLanguages as $otherLanguage)
                <ul>
                    <li>
                        <em>{!!  getLanguage($otherLanguage['language']) .' - '. checkIfEmpty($otherLanguage['narrative'] , 'Description Not Available')  !!}</em>
                    </li>
                </ul>
            @endforeach
        </div>
    </a>
@endif