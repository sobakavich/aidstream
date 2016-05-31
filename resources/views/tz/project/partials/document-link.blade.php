<div class="col-sm-12">
    <h2>Results/Outcomes Documents</h2>
    <div class="col-sm-6">
        {!! Form::label('result_document_title', 'Title', ['class' => 'control-label required']) !!}
        {!! Form::text('result_document_title', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
    <div class="col-sm-6">
        {!! Form::label('result_document_url', 'Document URL', ['class' => 'control-label required']) !!}
        {!! Form::text('result_document_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="col-sm-12">
    <h2>Annual Reports</h2>
    <div class="col-sm-6">
        {!! Form::label('annual_document_title', 'Title', ['class' => 'control-label required']) !!}
        {!! Form::text('annual_document_title', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <div class="col-sm-6">
        {!! Form::label('annual_document_url', 'Document Url', ['class' => 'control-label required']) !!}
        {!! Form::text('annual_document_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>
