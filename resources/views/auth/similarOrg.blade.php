<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>Aidstream - Register</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')
</head>
<body>
<header>
    <nav class="navbar navbar-default navbar-static navbar-fixed">
        <div class="navbar-header">
            <a href="{{ url('/') }}" class="navbar-brand">Aidstream</a>
            <button type="button" class="navbar-toggle collapsed">
                <span class="sr-only">Toggle navigation</span>
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </button>
        </div>
        <div class="navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a class="{{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                <li><a class="{{ Request::is('who-is-using') ? 'active' : '' }}" href="{{ url('/who-is-using') }}">Who's Using It?</a></li>
                <li><a href="https://github.com/younginnovations/aidstream-new/wiki/User-Guide" target="_blank">User Guide</a></li>
                <!--<li><a href="#">Snapshot</a></li>-->
            </ul>
            <div class="action-btn pull-left">
                @if(auth()->check())
                    <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? config('app.admin_dashboard') : config('app.super_admin_dashboard'))}}" class="btn btn-primary">Go
                        to Dashboard</a>
                @else
                    <a href="{{ url('/auth/login')}}" class="btn btn-primary">Login/Register</a>
                @endif
            </div>
        </div>
    </nav>
</header>

<div class="login-wrapper">
    {{--    <div class="language-select-wrapper">
            <label for="" class="pull-left">Language</label>

            <div class="language-selector pull-left">
                <span class="flag-wrapper"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ config('app.locale') }}"></span></span>
                <span class="caret pull-right"></span>
            </div>
            <ul class="language-select-wrap language-flag-wrap">
                @foreach(config('app.locales') as $key => $val)
                    <li class="flag-wrapper" data-lang="{{ $key }}"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ $key }}"></span><span class="language">{{ $val }}</span></li>
                @endforeach
            </ul>
        </div>--}}
    <div class="container-fluid register-container">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <span>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            </div>
                        @endif
                        <h1 class="text-center">Find your organization</h1>
                        <p class="text-center">
                            It seems there are account(s) on AidStream with same/similar organisation name you have entered during registration.
                            To help us recover your account details,please enter the name of your organisation in the field below.
                        </p>
                        <div class="similar-org-container">
                            {{--<div class="input-wrapper text-center {{ $orgName ? 'hidden' : '' }}">--}}
                                {{--Search for same/similar organisation name on AidStream.--}}
                            {{--</div>--}}

                            {{ Form::open(['url' => route('submit-similar-organization'), 'method' => 'post', 'id' => 'similar-org-form']) }}

                            <div class="input-wrapper">
                                <div class="col-xs-12 col-md-12 {{ $orgName ? 'hidden' : '' }}">
                                    {{ Form::hidden('type', $type) }}
                                    {!! AsForm::text(['name' => 'search_org', 'class' => 'search_org ignore_change', 'value' => $orgName, 'label' => false]) !!}
                                    {{ Form::button('Search Organisation', ['class' => 'btn btn-primary btn-search', 'type' => 'button']) }}
                                    {{ Form::hidden('similar_organization') }}
                                </div>
                                <div class="org-list-container clickable-org hidden">
                                    <div class="col-xs-12 col-md-12 organization-list-wrapper">
                                        <p class="text-center">Our database contains the following organisation/s which match the name of the organisation you entered. If one of them is your organistaion, please click to select it.</p>
                                        <ul class="organization-list">
                                        </ul>
                                    </div>
                                    <div class="col-md-12 text-center org-list-notification">
                                        <p>None of the results above match my organisation. I would like to <a href="{{ url('/register') }}">register</a>  my organisation for an Aidstream account.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 text-center clickable-org org-list-notification">
                                {{--<a data-value="" class="btn btn-continue">Continue with registration</a>--}}
                                {{ Form::button('Continue', ['class' => 'btn btn-primary btn-submit btn-register prevent-disable hidden', 'type' => 'submit', 'disabled' => 'disabled']) }}
                            </div>
                            {{ Form::close() }}
                        </div>
                        <div class="similar-org-action text-center hidden">
                            <h2>"<span class="org-name"></span>"</h2>
                            <div class="col-md-12 identifier-information">
                                <p>If this is your organisation you may do one of the followings</p>
                                <div class="col-sm-6">
                                    <h3>Administrator Information</h3>
                                    <p>
                                        The administrator of the organisation name is
                                    </p>
                                    <span class="admin-name"></span>
                                    <a href="{{ route('contact', ['contact-admin-for-same-org']) }}" class="btn btn-primary">Contact Administrator</a>
                                </div>
                                <div class="col-sm-6">
                                    <h3>Retrieve Login Credentials</h3>
                                    <p>
                                        I already have an account but forgotten my login credentials.
                                    </p>
                                    <a href="{{ route('contact', ['contact-support-for-same-org']) }}" class="btn btn-primary">Contact Support for assistance</a>
                                </div>
                            </div>
                            <button class="btn btn-back">Back to Organisation List</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 create-account-wrapper">
                <a href="{{ url('/auth/login') }}">I already have an account</a>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

<script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{url('/js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/registration.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('form select').select2();
        Registration.filterSimilarOrg();
    });
</script>
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
</body>
</html>
