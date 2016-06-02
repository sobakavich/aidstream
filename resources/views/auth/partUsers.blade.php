<div class="user-block clearfix" style="background-color: #EBF8FF; margin-bottom: 10px;">
    <a href="#delete" class="delete pull-right">remove</a>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::username(['name' => 'user[' . $userIndex . '][username]', 'hiddenName' => 'user[' . $userIndex . '][login_username]', 'addOnValue' => $identifier, 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'class' => 'username']) !!}
        {!! AsForm::email(['name' => 'user[' . $userIndex . '][email]', 'label' => 'E-mail Address', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'user[' . $userIndex . '][first_name]', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        {!! AsForm::text(['name' => 'user[' . $userIndex . '][last_name]', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::select(['name' => 'user[' . $userIndex . '][role]', 'label' => 'Permission Role', 'data' => $roles, 'empty_value' => 'Select a Role', 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
</div>
