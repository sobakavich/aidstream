@if(!emptyOrHasEmptyTemplate($activityDates))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.activity_date')</dt>
                <dd>
                @foreach(groupActivityElements($activityDates , 'type') as $key => $groupedDates)
                    <dt>{{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} @lang('activityView.date')</dt>
                    <dd>
                        @foreach($groupedDates as $groupedDate)
                            <li>{{ formatDate($groupedDate['date']) }}</li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>{{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} @lang('activityView.description')
                                    : {!! checkIfEmpty(getFirstNarrative($groupedDate)) !!}</dl>
                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($groupedDate['narrative'])])

                            </dl>
                            <hr>
                @endforeach
                @endforeach
                <dd>
                </dd>

            {{--<a href="{{route('activity.activity-date.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'activity_date'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
