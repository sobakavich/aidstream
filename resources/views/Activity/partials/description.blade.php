@if(!emptyOrHasEmptyTemplate($descriptions))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <dl class="dl-horizontal">
                <dt>@lang('activityView.description')</dt>
                <dd>
                @foreach($descriptions as $description)
                    <dt>{{$getCode->getCodeNameOnly('DescriptionType', $description['type'])}} Description</dt>
                    <dd>
                        {!! getFirstNarrative($description) !!}
                        @include('Activity.partials.viewInOtherLanguage' , ['otherLanguages' => getOtherLanguages($description['narrative']) ])
                        @endforeach
                    </dd>
            </dl>


            {{--<a href="{{route('activity.description.index', $id)}}" class="edit-element">edit</a>--}}
            {{--<a href="{{route('activity.delete-element', [$id, 'description'])}}" class="delete pull-right">remove</a>--}}
        </div>

    </div>
@endif
