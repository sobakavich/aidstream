@if(!emptyOrHasEmptyTemplate($participatingOrganizations))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.participating_organization')</div>
        @foreach(groupActivityElements($participatingOrganizations , 'organization_role') as $key => $organizations)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $getCode->getCodeNameOnly('OrganisationRole', $key)}} Organization(s)
                </div>
                <div class="activity-element-info">
                    @foreach($organizations as $organization)
                        <li>
                            {!!  getFirstNarrative($organization)  !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($organization['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.identifier')</dt>
                                <dd>{!! checkIfEmpty($organization['identifier']) !!}</dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.organization_type')</dt>
                                <dd>
                                    @if(!empty($organization['organization_type']))
                                        {{$organization['organization_type'] . ' - ' . $getCode->getCodeNameOnly("OrganisationType",$organization['organization_type']) }}
                                    @else
                                        <em>Not Available</em>
                                    @endif
                                </dd>
                            </dl>
                            <dl>
                                <dt>@lang('activityView.organization_role')</dt>
                                <dd>{{$organization['organization_role'] . ' - ' . $getCode->getCodeNameOnly("OrganisationRole",$organization['organization_role']) }}</dd>
                            </dl>
                            @if(session('version') != 'V201')
                                @if(array_key_exists('activity_id' , $organization))
                                    <dl>
                                        <dt>@lang('activityView.activity_id')</dt>
                                        <dd>{!! checkIfEmpty($organization['activity_id']) !!}</dd>
                                    </dl>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.participating-organization.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'participating_organization'])}}" class="delete pull-right">remove</a>
    </div>
@endif
