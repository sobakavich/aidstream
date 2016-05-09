@if(!emptyOrHasEmptyTemplate($otherIdentifiers))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.other_identifier')</dt>
                <dd>
                @foreach(groupActivityElements($otherIdentifiers , 'type') as $key => $groupedIdentifiers)
                    <dt>{{$key}} @lang('activityView.rep_org_internal_acitivity_identifier')</dt>
                    <dd>
                        @foreach($groupedIdentifiers as $identifiers)
                            <li>{{ $identifiers['reference'] }}</li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>@lang('activityView.owner_org_reference')
                                    : {{ $identifiers['owner_org'][0]['reference'] }}
                                </dl>

                                <dl>@lang('activityView.owner_org_name'):
                                    {!! getFirstNarrative($identifiers['owner_org'][0]) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages(getOwnerNarrative($identifiers))])

                                </dl>
                            </dl>

                        @endforeach
                    </dd>
                    <hr>
                @endforeach


            </dl>

        </div>
        {{--<a href="{{route('activity.other-identifier.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'other_identifier'])}}" class="delete pull-right">remove</a>--}}
    </div>

@endif
