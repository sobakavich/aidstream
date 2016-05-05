@if(!emptyOrHasEmptyTemplate($reportingOrganization))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.reporting_organization')</dt>
                <dd>
                    {{ $reportingOrganization['narrative'][0]['narrative'] }} <em>(lang:{{ getLanguage($reportingOrganization['narrative'][0]['language']) }})</em>
                    <a href="#" class="show-more-info">Show more info</a>
                    <a href="#" class="hide-more-info hidden">Hide more info</a>
                </dd>
                <dl class="more-info hidden">
                    <dt>Description</dt>
                    <dd>The project will significantly extend the range of health care sevice provision at Baghbanan HC for
                        approximately 29,800 women, men, girls and boys from Baghbanan community. This will improve their health
                        status and contribute to achieving MDG4 and MDG5 in this poor and marginalised population with limited
                        access to affordable primary health care. Staff and local health care providers will be recruited
                        locally
                        and trained, thus building capacity for a sustainable future.
                        <a href="#" class="view-other-language">View in other languages
                            <div class="hidden">
                                <ul>
                                    <li>nl-unite for Body Rights Ethiopia</li>
                                    <li>fr-unite for Body Droits Ethiopia</li>
                                </ul>
                            </div>
                        </a>

                    </dd>
                </dl>
            </dl>
        </div>
    </div>

@endif
