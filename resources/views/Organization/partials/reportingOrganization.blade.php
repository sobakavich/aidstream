@if(!emptyOrHasEmptyTemplate($reporting_org))
    <dl class="dl-horizontal">
        <dt>@lang('activityView.reporting_organization')</dt>
        <dd>
            <dl>{!! checkIfEmpty(getFirstNarrative($reporting_org)) !!}
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($reporting_org['narrative'])])
            </dl>
            <a href="#" class="show-more-info">Show more info</a>
            <a href="#" class="hide-more-info hidden">Hide more info</a>                 
            <div class="more-info-hidden">
                <dl>@lang('activityView.identifier')
                    : {!! checkIfEmpty($reporting_org['reporting_organization_identifier']) !!}
                </dl>

                <dl>@lang('activityView.organization_type')
                    : {!! getCodeNameWithCodeValue('OrganisationType' , $reporting_org['reporting_organization_type'] , -4) !!}
                </dl>
            </div>
        </dd>
    </dl>
    {{--<a href="{{ url('/organization/' . $orgId . '/reportingOrg') }}" class="edit-element">edit</a>--}}
@endif
