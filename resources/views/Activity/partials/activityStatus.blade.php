@if(!empty($activityStatus))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.activity_status')</dt>
                <dd>
                {{ $getCode->getCodeNameOnly('ActivityStatus', $activityStatus) }}
            </dl>
            </div>
            {{--<a href="{{route('activity.activity-status.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'activity_status'])}}" class="delete pull-right">remove</a>--}}
        </div>
@endif
