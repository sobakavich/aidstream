@if(!empty($conditions))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.conditions')</div>
        @if($conditions['condition_attached'] == 0)
            <div class="activity-element-list">
                <div class="activity-element-label">@lang('activityView.condition_not_attached')</div>
            </div>
        @else
            @foreach(groupActivityElements($conditions['condition'], 'condition_type') as $key => $condition)
                <div class="activity-element-list">
                    <div class="activity-element-label">
                        {{ $getCode->getCodeNameOnly('ConditionType',$key) }}
                    </div>
                    <div class="activity-element-info">
                        @foreach($condition as $conditionInfo)
                            <li>
                                {!! getFirstNarrative($conditionInfo) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($conditionInfo['narrative'])])
                            </li>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
        <a href="{{route('activity.condition.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'condition'])}}" class="delete pull-right">remove</a>
    </div>
@endif
