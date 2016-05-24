@if(!emptyOrHasEmptyTemplate($activityDates))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.activity_date')</div>
        @foreach(groupActivityElements($activityDates , 'type') as $key => $groupedDates)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} @lang('activityView.date')
                </div>
                <div class="activity-element-info">
                    @foreach($groupedDates as $groupedDate)
                        <li>{{ formatDate($groupedDate['date']) }}</li>
                        <div class="toggle-btn">
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                        </div>
                        <div class="more-info">
                            <dl>
                                <dt>{{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} @lang('activityView.description')</dt>
                                <dd>{!! checkIfEmpty(checkIfEmpty(getFirstNarrative($groupedDate))) !!}</dd>
                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($groupedDate['narrative'])])
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        {{--<a href="{{route('activity.activity-date.index', $id)}}" class="edit-element">edit</a>--}}
        {{--<a href="{{route('activity.delete-element', [$id, 'activity_date'])}}" class="delete pull-right">remove</a>--}}
    </div>
@endif
