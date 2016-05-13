@if(!empty($conditions))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.conditions')</dt>
                <dd>
                @if($conditions['condition_attached'] == 0)
                    <dt>@lang('activityView.condition_not_attached')</dt>
                @else
                    @foreach(groupConditionElements($conditions) as $key => $conditions)
                        <dt>{{ $getCode->getCodeNameOnly('ConditionType',$key) }}</dt>
                        <dd>
                            {!! getFirstNarrative($conditions) !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($conditions['narrative'])])
                            <hr>
                            @endforeach
                        </dd>
                        @endif
            </dl>
            {{--<a href="{{route('activity.condition.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'conditions'])}}" class="delete pull-right">remove</a>--}}
        </div>
        <div class="panel-body panel-level-1">
            <div class="panel panel-default">
                <div class="panel-element-body">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Attached:</div>
                        {{--<div class="col-xs-12 col-sm-8"> {{($conditions['condition_attached'] == "1") ? 'Yes' : 'No' }}</div>--}}
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Description</div>
                <div class="panel-body row">
                    @if(!empty($conditions['condition']))
                        @foreach($conditions['condition'] as $data)
                            <div class="panel panel-default">
                                <div class="panel-element-body">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-sm-4">Type:</div>
                                        {{--<div class="col-xs-12 col-sm-8">{{$getCode->getActivityCodeName('ConditionType', $data['condition_type'])}}</div>--}}
                                    </div>
                                    @foreach($data['narrative'] as $narrative)
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-sm-4">Text:</div>
                                            <div class="col-xs-12 col-sm-8">{{$narrative['narrative'] . hideEmptyArray('Organization', 'Language', $narrative['language'])}}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
