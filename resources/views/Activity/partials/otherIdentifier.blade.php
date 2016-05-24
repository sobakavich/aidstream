@if(!emptyOrHasEmptyTemplate($otherIdentifiers))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.other_identifier')</div>
        @foreach(groupActivityElements($otherIdentifiers , 'type') as $key => $groupedIdentifiers)
            <div class="activity-element-list">
                <div class="activity-element-label">{{$key}} @lang('activityView.rep_org_internal_acitivity_identifier')</div>
                <div class="activity-element-info">
                    @foreach($groupedIdentifiers as $identifiers)
                        <li>{{$identifiers['reference']}}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>@lang('activityView.owner_org_reference')</dt>
                                <dd>{!! checkIfEmpty($identifiers['owner_org'][0]['reference']) !!}</dd>
                            </dl>

                            <dl>
                                <dt>@lang('activityView.owner_org_name')</dt>
                                <dd>{!! checkIfEmpty(getFirstNarrative($identifiers['owner_org'][0])) !!}</dd>
                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages(getOwnerNarrative($identifiers))])
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
        {{--<a href="{{route('activity.other-identifier.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'other_identifier'])}}" class="delete pull-right">remove</a>--}}
@endif
