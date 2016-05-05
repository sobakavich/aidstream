@if(!emptyOrHasEmptyTemplate($activityDates))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.activity_date')</dt>
                <dd>
                @foreach(groupActivityElements($activityDates , 'type') as $key => $groupedDates)
                    <dt>{{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} Date</dt>
                    <dd>
                        @foreach($groupedDates as $groupedDate)
                            <li>{{ formatDate($groupedDate['date']) }}</li>
                            <a href="#" class="show-more-info">Show more info</a>
                            <a href="#" class="hide-more-info hidden">Hide more info</a>
                            <dl class="more-info-hidden">
                                <dl>{{ $getCode->getCodeNameOnly('ActivityDateType', $key) }} Description
                                    : {!! getFirstNarrative($groupedDate) !!}</dl>

                                @include('Activity.partials.viewInOtherLanguage' ,$otherLanguages = getOtherLanguages($groupedDate['narrative']))

                            </dl>
                            <hr>
                @endforeach
                @endforeach
                <dd>
                </dd>

            {{--<a href="{{route('activity.activity-date.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'activity_date'])}}" class="delete pull-right">remove</a>--}}
        </div>
        <div class="panel-body panel-level-1">
            @foreach($activityDates as $activity_date)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        {{$getCode->getActivityCodeName('ActivityDateType', $activity_date['type'])}}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Type:</div>
                            <div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ActivityDateType', $activity_date['type'])}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Date:</div>
                            <div class="col-xs-12 col-sm-8">{{ formatDate($activity_date['date']) }}</div>
                        </div>
                        @foreach($activity_date['narrative'] as $narrative)
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Text:</div>
                                <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
