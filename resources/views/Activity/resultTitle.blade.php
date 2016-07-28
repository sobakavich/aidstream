<input type="checkbox" name="activities[]"
       value="{{ json_encode($result['data']) }}" class="pull-left"
       @if($result['errors'] || $isDuplicate) disabled="disabled" @endif>
<div class="activity-title">
    @if($title = $result['data']['activity_title'])
        {{ $title }}
    @else
        <div class="no-title">(No Title)</div>
    @endif
</div>
