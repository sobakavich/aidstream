<div class="panel panel-default">
    @if ($activities)
        @foreach ($activities as $index => $activity)
            <div class="panel-heading">
                <label>
                    <input type="checkbox" disabled="disabled" value="{{ $index }}"/>
                    <h3>
                        <span class="panel-title">
                            {{ getVal($activity, ['data', 'identifier', 'activity_identifier'], '') }} - {{ getVal($activity, ['data', 'title', 0, 'narrative']) }}
                        </span>
                    </h3>
                    <div>
                        @foreach (getVal($activity, ['errors'], []) as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </div>
                    <span class="panel-content-heading">
                    </span>
                </label>
            </div>
        @endforeach
    @endif
</div>
