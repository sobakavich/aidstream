<div class="col-sm-12">
    <h2>Location</h2>
    <div class="col-sm-12" id="location-wrap">
        <div class="col-sm-4" id="region-wrap">
            {!! Form::label('location[0][administrative][0][code]', 'Region', ['class' => 'control-label']) !!}
            {!! Form::text('location[0][administrative][0][code]', null, ['class' => 'form-control region', 'required' => 'required']) !!}

            {!! Form::hidden('location[0][administrative][0][vocabulary]', 'G1') !!}
            {!! Form::hidden('location[0][administrative][0][level]', '1') !!}
        </div>

        <div class="col-sm-4" id="district-wrap">
            {!! Form::label('location[0][administrative][1][code]', 'District', ['class' => 'control-label']) !!}
            {!! Form::text('location[0][administrative][1][code]', null, ['class' => 'form-control district', 'required' => 'required']) !!}

            {!! Form::hidden('location[0][administrative][1][vocabulary]', 'G1') !!}
            {!! Form::hidden('location[0][administrative][1][level]', '2') !!}
        </div>
        <button type="button" id="add-more-location" class="add-more">Add More</button>
    </div>
</div>
