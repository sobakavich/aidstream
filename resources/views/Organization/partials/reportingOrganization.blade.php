@if(!emptyOrHasEmptyTemplate($reporting_org))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.reporting_organization')</div>
            <div class="activity-element-info">
                {!! checkIfEmpty(getFirstNarrative($reporting_org)) !!}
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($reporting_org['narrative'])])
                <div class="toggle-btn">
                    <a href="#" class="show-more-info">Show more info</a>
                    <a href="#" class="hide-more-info hidden">Hide more info</a>                 
                </div>
                <div class="more-info">
                    <dl>
                        <dt>@lang('activityView.identifier')</dt>
                        <dd>{!! checkIfEmpty($reporting_org['reporting_organization_identifier']) !!}</dd>
                    </dl>
                    <dl>
                        <dt>@lang('activityView.organization_type')</dt>
                        <dd>{!! getCodeNameWithCodeValue('OrganisationType' , $reporting_org['reporting_organization_type'] , -4) !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ url('/organization/' . $orgId . '/reportingOrg') }}" class="edit-element">edit</a>
@endif
