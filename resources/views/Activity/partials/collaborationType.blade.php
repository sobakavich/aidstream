@if(!empty($collaborationType))
    <div class="panel panel-default">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.collaboration_type')</dt>
                <dd>
                    {{ $getCode->getCodeNameOnly('CollaborationType' , $collaborationType) }}
                </dd>
            </dl>
            {{--<a href="{{route('activity.collaboration-type.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'collaboration_type'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
