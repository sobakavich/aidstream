@if(!empty($defaultFlowType))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.default_flow_type')</dt>
                <dd>
                    {{ substr($getCode->getActivityCodeName('FlowType', $defaultFlowType) , 0 , -4)}}
                </dd>

            </dl>
            {{--<a href="{{route('activity.default-flow-type.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'default_flow_type'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
