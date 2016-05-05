@if(!emptyOrHasEmptyTemplate($identifier))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.activity_identifier')</dt>
                <dd>
                    {{ $identifier['iati_identifier_text'] }}

                </dd>
            </dl>
            {{--<a href="{{route('activity.iati-identifier.index', $id)}}" class="edit-element">edit</a>--}}
        </div>
    </div>
@endif
