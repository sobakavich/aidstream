@if(!empty($defaultFinanceType))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.default_finance_type')</dt>
                <dd>
                    {{ substr($getCode->getActivityCodeName('FinanceType', $defaultFinanceType) , 0 , -5)}}
                </dd>

            </dl>
            {{--<a href="{{route('activity.default-finance-type.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'default_finance_type'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
