{{--*/
$messages = $errors->get($validationName);
/*--}}
<div class="form-group {{ $parent }}{{ $messages ? ' has-error' : '' }}">
    {{ Form::label($name, $label, ['class' => sprintf('control-label %s', ($required ? 'required' : ''))]) }}
    <div class="col-xs-12 col-md-12">
        @include(sprintf('forms.%s', $field))
        {!! $html !!}
        @foreach($messages as $message)
            <div class="text-danger">{{ $message }}</div>
        @endforeach
    </div>
</div>
