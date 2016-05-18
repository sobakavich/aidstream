@if(!emptyOrHasEmptyTemplate($legacyDatas))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.legacy_data')</dt>
                <dd>
                    @foreach($legacyDatas as $legacyData)
                        <li>{{ $legacyData['name'] . ':'. $legacyData['value'] }}</li>
                        <em>@lang('activityView.iati_equivalent')
                            : {!!   checkIfEmpty($legacyData['iati_equivalent']) !!}</em>
                    @endforeach
                </dd>

            {{--<a href="{{route('activity.legacy-data.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'legacy_data'])}}" class="delete pull-right">remove</a>--}}
        </div>
    </div>
@endif
