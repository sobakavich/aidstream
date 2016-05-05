@if(! empty($otherLanguages))
    <a href="#" class="view-other-language">
        <em>@lang('activityView.view_in_other_languages')</em>
        <div class="hidden">
            @foreach($otherLanguages as $otherLanguage)
                <ul>
                    <li><em>{{ getLanguage($otherLanguage['language']) .'-'. $otherLanguage['narrative'] }}</em></li>
                </ul>
            @endforeach
        </div>
    </a>
@endif