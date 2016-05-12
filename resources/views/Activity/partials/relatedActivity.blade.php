@if(!emptyOrHasEmptyTemplate($relatedActivities))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.related_activity')</dt>
                <dd>
                @foreach(groupActivityElements($relatedActivities , 'relationship_type') as $key => $relatedActivities)
                    <dt>
                        {!! $getCode->getCodeNameOnly('RelatedActivityType' , $key) !!}
                    </dt>
                    <dd>
                        @foreach($relatedActivities as $relatedActivity)
                            <li> {{ $relatedActivity['activity_identifier'] }}</li>
                        @endforeach
                        <hr>
                    <dd>
                        @endforeach
                    </dd>
            </dl>
            {{--<a href="{{route('activity.related-activity.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'related_activity'])}}"--}}
            {{--class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
