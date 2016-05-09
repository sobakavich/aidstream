@if(!empty($defaultTiedStatus))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.default_tied_status')</dt>
                <dd>
                    {{ substr($getCode->getActivityCodeName('TiedStatus', $defaultTiedStatus) , 0 , -4)}}
                </dd>

            </dl>
            {{--<a href="{{route('activity.default-tied-status.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'default_tied_status'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
