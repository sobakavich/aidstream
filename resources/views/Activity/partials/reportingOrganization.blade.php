@if(!emptyOrHasEmptyTemplate($reportingOrganization))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.reporting_organization')</dt>
                <dd>
                    {!! checkIfEmpty(getFirstNarrative($reportingOrganization)) !!}
                    <a href="#" class="show-more-info">Show more info</a>
                    <a href="#" class="hide-more-info hidden">Hide more info</a>
                </dd>
                <dl class="more-info hidden">
                    <dl>@lang('activityView.organization_identifier')
                        : {!! checkIfEmpty($reportingOrganization['reporting_organization_identifier']) !!}
                    </dl>

                    <dl>@lang('activityView.organization_type')
                        : {!! substr($getCode->getOrganizationCodeName('OrganizationType' , $reportingOrganization['reporting_organization_type']) , 0 , -4) !!}
                    </dl>
                </dl>
            </dl>
        </div>
    </div>

@endif
