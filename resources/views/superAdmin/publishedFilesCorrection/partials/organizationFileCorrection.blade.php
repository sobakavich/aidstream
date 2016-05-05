<table>
    <thead>
    <tr>
        <th>S. N.</th>
        <th>Organization File</th>
        <th>Actions</th>
    </tr>
    </thead>
    <div class="pull-right">
        <strong>
            <a href="{{ route('superadmin.reSyncOrganizationData', $organization->id) }}">Sync</a>
        </strong>
    </div>
    <tbody>
    @forelse ($organizationPublishedFiles as $index => $publishedFile)
        <tr>
            <td>
                {{ $index + 1 }}
            </td>
            <td>
                @if (file_exists(public_path('/files/xml/') . '/' . $publishedFile->filename))
                    <a href="{{ url('/files/xml/') . '/' . $publishedFile->filename }}">{{ $publishedFile->filename }}</a>
                @else
                    {{ $publishedFile->filename }}
                @endif
            </td>
            <td>
                <a href="{{ route('superadmin.unlinkOrganizationXmlFile', [$organization->id, $publishedFile->id]) }}">Unlink</a>

                @if (!$publishedFile->published_to_register)
                    {!! Form::open(['method' =>'DELETE', 'url' => route('superadmin.deleteOrganizationXmlFile', ['organizationId' => $organization->id, 'fileId' => $publishedFile->id])]) !!}
                    {!! Form::submit('Delete') !!}
                    {!! Form::close() !!}
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center">
                <b>No Files Found.</b>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>