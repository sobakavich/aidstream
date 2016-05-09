@if(!emptyOrHasEmptyTemplate($contactInfo))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.contact_info')</dt>
                <dd>
                @foreach(groupActivityElements($contactInfo , 'type') as $key => $contactInformation)
                    <dt>{{ $getCode->getCodeNameOnly('ContactType' , $key) }}</dt>
                    <dd>
                        @foreach($contactInformation as $information)
                            <li> {!! getFirstNarrative($information['person_name'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['person_name'][0]['narrative'])])
                                ,{!! getFirstNarrative($information['organization'][0]) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['organization'][0]['narrative'])]) </li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.department')
                                    : {!!  getFirstNarrative($information['department'][0])  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['department'][0]['narrative'])])
                                </dl>
                                <dl>@lang('activityView.job_title')
                                    :{!! getFirstNarrative($information['job_title'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['job_title'][0]['narrative'])])
                                </dl>

                                <dl>@lang('activityView.telephone')
                                    : {!! getContactInfo('telephone', $information['telephone'])  !!}
                                </dl>

                                <dl>@lang('activityView.email')
                                    : {!! getContactInfo('email', $information['email']) !!}
                                </dl>
                                <dl>@lang('activityView.website')
                                    :{!! getContactInfo('website' , $information['website']) !!}
                                </dl>

                                <dl>@lang('activityView.mailing_address')
                                    : {!!  getFirstNarrative($information['mailing_address'][0])  !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($information['mailing_address'][0]['narrative'])])
                                </dl>
                            </dl>

                        @endforeach
                        <hr>
                @endforeach
            </dl>

            {{--<a href="{{route('activity.contact-info.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'contact_info'])}}" class="delete pull-right">remove</a>--}}
        </div>

    </div>
@endif
