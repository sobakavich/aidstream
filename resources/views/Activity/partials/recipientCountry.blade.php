@if(!emptyOrHasEmptyTemplate($recipientCountries))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.recipient_country')</div>
            <div class="activity-element-info">
                @foreach($recipientCountries as $recipientCountry)
                    <li>{!! getRecipientInformation($recipientCountry['country_code'], $recipientCountry['percentage'], 'Country') !!}</li>
                    <div class="toggle-btn">
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                    </div>
                    <div class="more-info">
                        <dl>
                            <dt>@lang('activityView.description')</dt>
                            <dd>
                                {!! checkIfEmpty(getFirstNarrative($recipientCountry)) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientCountry['narrative'])])
                            </dd>
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{route('activity.recipient-country.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'recipient_country'])}}" class="delete pull-right">remove</a>
    </div>
@endif
