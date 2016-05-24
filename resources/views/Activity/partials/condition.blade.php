@if(!empty($conditions))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.conditions')</div>
        <div class="activity-element-list">
            @if($conditions['condition_attached'] == 0)
                <div class="activity-element-label">@lang('activityView.condition_not_attached')</div>
            @else
                @foreach(groupConditionElements($conditions) as $key => $conditions)
                    <div class="activity-element-label">{{ $getCode->getCodeNameOnly('ConditionType',$key) }}</div>
                    <div class="activity-element-info">
                        <li>
                            {!! getFirstNarrative($conditions) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($conditions['narrative'])])
                        </li>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endif
