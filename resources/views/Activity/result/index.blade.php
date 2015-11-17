@extends('app')

@section('content')

    {{Session::get('message')}}

    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Results</div>

                    <div class="panel-body">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>S.N.</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($results as $resultIndex=>$result)
                                {{--*/ $title = $result->result[0]['title'][0]['narrative'][0]['narrative'] /*--}}
                                <tr>
                                    <td><input type="checkbox"/></td>
                                    <td>{{ $resultIndex + 1 }}</td>
                                    <td class="activity_title">
                                        {{ $title == "" ? 'No Title' : $title }}
                                    </td>
                                    <td>
                                        {{ $result->result[0]['type'] }}
                                    </td>
                                    <td>
                                        <div class="activity_actions">
                                            <a href="#">View</a>
                                            <a href="{{ route('activity.result.edit', [$id, $result->id]) }}">Edit</a>
                                            <a href="#">Delete</a>
                                        </div>
{{--                                        <div class="activity_actions">
                                            <a href="{{ route('activity.result.index', $id) }}">View</a>
                                            <a href="{{ route('activity.show', [$activity->id]) }}">View</a>
                                            <a href="{{ route('activity.destroy', [$activity->id]) }}">Delete</a>
                                        </div>--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No activities found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <a href="{{ route('activity.result.create', $id) }}">Add Another Result</a>

                    </div>
                </div>
            </div>
            @include('includes.side_bar_menu')
        </div>
    </div>
@endsection

