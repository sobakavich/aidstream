<dl class="more-info">
    <dl> @lang('activityView.location_ref'):</dl>
    <dd>
        {!! getTargetAdditionalDetails($period[$type] , 'locationRef')!!}
    </dd>

    <dl>@lang('activityView.dimension'):</dl>
    <dd>
        {!! getTargetAdditionalDetails($period[$type] , 'dimension')!!}
    </dd>
    <dl>@lang('Description'):</dl>
    <dd>
        {!! getFirstNarrative($period[$type]['comment'][0]) !!}
        @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($period['target']['comment'][0]['narrative'])])
    </dd>

</dl>
