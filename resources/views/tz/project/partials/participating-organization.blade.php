<div class="col-sm-12" id="funding-wrap">
    <h2>Funding</h2>

    <div class="col-sm-6">
        {!! Form::label('funding_organization[0][funding_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
        {!! Form::text('funding_organization[0][funding_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <div class="col-sm-6">
        {!! Form::label('funding_organization[0][funding_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
        {!! Form::select('funding_organization[0][funding_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
    <button type="button" id="add-more-funding-organization" class="add-more">Add More Funding Organization</button>
</div>
<div class="col-sm-12" id="implementing-wrap">
    <h2>Implementing</h2>
    <div class="col-sm-6">
        {!! Form::label('implementing_organization[0][implementing_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
        {!! Form::text('implementing_organization[0][implementing_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <div class="col-sm-6">
        {!! Form::label('implementing_organization[0][implementing_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
        {!! Form::select('implementing_organization[0][implementing_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
    <button type="button" id="add-more-implementing-organization" class="add-more">Add More Implementing Organization</button>
</div>
