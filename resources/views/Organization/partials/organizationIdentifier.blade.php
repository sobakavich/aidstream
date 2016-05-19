@if(!emptyOrHasEmptyTemplate($reporting_org))
    <dl class="dl-horizontal">
        <dt>@lang('activityView.organization_identifier')</dt>
        <dd>
            {{ $reporting_org['reporting_organization_identifier'] }}
        </dd>
    </dl>
@endif