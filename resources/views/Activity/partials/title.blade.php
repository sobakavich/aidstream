@if(!emptyOrHasEmptyTemplate($titles))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.activity_title')</dt>
                <dd>
                    {{ $titles[0]['narrative'] }}
                    <em>(language: {{ getLanguage($titles[0]['language']) }})</em>

                    @include('Activity.partials.viewInOtherLanguage' ,$otherLanguages = $titlesExceptFirstElement)
                </dd>
            </dl>

            {{--<a href="{{route('activity.title.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'title'])}}" class="delete pull-right">remove</a>--}}
        </div>

    </div>
@endif
