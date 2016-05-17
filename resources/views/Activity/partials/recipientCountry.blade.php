@if(!emptyOrHasEmptyTemplate($recipientCountries))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.recipient_country')</dt>
                <dd>
                    @foreach($recipientCountries as $recipientCountry)
                        <li>{!! getRecipientInformation($recipientCountry['country_code'], $recipientCountry['percentage'], 'Country') !!}</li>
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>
                        <dl class="hidden-info">@lang('activityView.description')
                            :{!! checkIfEmpty(getFirstNarrative($recipientCountry)) !!}
                        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientCountry['narrative'])])
                        </dl>
                    @endforeach
                </dd>
            {{--<a href="{{route('activity.recipient-country.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'recipient_country'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
