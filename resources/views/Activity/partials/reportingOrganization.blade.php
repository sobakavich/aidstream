@if(!emptyOrHasEmptyTemplate($reportingOrganization))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.reporting_organization')</div>
            <div class="activity-element-info">
                <li>{!! checkIfEmpty(getFirstNarrative($reportingOrganization)) !!}</li>
                <div class="toggle-btn">
                    <a href="#" class="show-more-info">Show more info</a>
                    <a href="#" class="hide-more-info hidden">Hide more info</a>
                </div>

                <div class="more-info">
                    <dl>
                        <dt>@lang('activityView.organization_identifier')</dt>
                        <dd>{!! checkIfEmpty($reportingOrganization['reporting_organization_identifier']) !!}</dd>
                    </dl>
                    <dl>
                        <dt>@lang('activityView.organization_type')</dt>
                        <dd>{!! substr($getCode->getOrganizationCodeName('OrganizationType', $reportingOrganization['reporting_organization_type']), 0, -4) !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endif
