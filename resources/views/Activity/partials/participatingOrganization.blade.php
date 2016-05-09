@if(!emptyOrHasEmptyTemplate($participatingOrganizations))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.participating_organization')</dt>
                <dd>
                @foreach(groupActivityElements($participatingOrganizations , 'organization_role') as $key => $organizations)
                    <dt>{{ $getCode->getCodeNameOnly('OrganisationRole', $key)}} Organization(s)</dt>
                    <dd>
                        @foreach($organizations as $organization)
                            <li>{!!  getFirstNarrative($organization)  !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($organization['narrative'])])
                            </li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.identifier')
                                    : {!! checkIfEmpty($organization['identifier']) !!} </dl>
                                <dl>@lang('activityView.organization_type')
                                    :@if(!empty($organization['organization_type']))
                                        {{$organization['organization_type'] . ' - ' . $getCode->getCodeNameOnly("OrganisationType",$organization['organization_type']) }}
                                    @else
                                        <em>Not Available</em>
                                    @endif
                                </dl>
                                <dl>@lang('activityView.organization_role')
                                    : {{$organization['organization_role'] . ' - ' . $getCode->getCodeNameOnly("OrganisationRole",$organization['organization_role']) }}
                                </dl>
                            </dl>
                            <hr>
                        @endforeach

                    </dd>
                @endforeach
            </dl>
            {{--<a href="{{route('activity.participating-organization.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'participating_organization'])}}" class="delete pull-right">remove</a>--}}
        </div>

    </div>
@endif
