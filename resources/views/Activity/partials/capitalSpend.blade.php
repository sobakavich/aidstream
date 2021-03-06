@if(!empty($capitalSpend))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.capital_spend')</div>
            <div class="activity-element-info">
                {{ $capitalSpend.'%' }}
            </div>
        </div>
        <a href="{{route('activity.capital-spend.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'capital_spend'])}}" class="delete pull-right">remove</a>
    </div>
@endif
