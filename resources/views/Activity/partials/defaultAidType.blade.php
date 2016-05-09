@if(!empty($defaultAidType))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.default_aid_type')</dt>
                <dd>
                    {{ substr($getCode->getActivityCodeName('AidType', $defaultAidType) , 0 , -5)}}
                </dd>

            </dl>
            {{--<a href="{{route('activity.default-aid-type.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'default_aid_type'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
