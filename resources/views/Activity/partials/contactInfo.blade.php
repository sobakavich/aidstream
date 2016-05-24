@if(!emptyOrHasEmptyTemplate($contactInfo))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.contact_info')</div>
        @foreach(groupActivityElements($contactInfo , 'type') as $key => $contactInformation)
            <div class="activity-element-list">
                <div class="activity-element-label">{{ $getCode->getCodeNameOnly('ContactType' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($contactInformation as $information)
                        <li>
                            {!! getFirstNarrative($information['person_name'][0]) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['person_name'][0]['narrative'])])
                            ,{!! getFirstNarrative($information['organization'][0]) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['organization'][0]['narrative'])])
                        </li>

                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.department')</dt>
                                <dd>
                                    {!!  getFirstNarrative($information['department'][0])  !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['department'][0]['narrative'])])
                                </dd>

                            </dl>
                            <dl>
                                <dt>@lang('activityView.job_title')</dt>
                                <dd>
                                    {!! getFirstNarrative($information['job_title'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($information['job_title'][0]['narrative'])])
                                </dd>

                            </dl>
                            <dl>
                                <dt>@lang('activityView.telephone')</dt>
                                <dd>{!! getContactInfo('telephone', $information['telephone'])  !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.email')</dt>
                                <dd>{!! getContactInfo('email', $information['email']) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.website')</dt>
                                <dd>{!! getContactInfo('website' , $information['website']) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.mailing_address')</dt>
                                <dd>
                                    {!!  getFirstNarrative($information['mailing_address'][0])  !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($information['mailing_address'][0]['narrative'])])
                                </dd>

                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.contact-info.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'contact_info'])}}" class="delete pull-right">remove</a>
    </div>
@endif
