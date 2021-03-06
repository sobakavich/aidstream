<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>Aidstream - Forgot Password</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

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
                <li><a class="{{ Request::is('who-is-using') ? 'active' : '' }}" href="{{ url('/who-is-using') }}">Who's Using</a></li>
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

<div class="register-wrapper">
    {{--		    <div class="language-select-wrapper">
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
    <div class="container-fluid login-container contact-admin-container">
        <div class="row">
            <h1 class="text-center">{{ $contactTitle }}</h1>
            {{--*/
                $template = request()->route()->template;
            /*--}}
            @if($template == 'need-new-user' || $template == 'forgot-user-email' || $template == 'contact-admin-for-same-org')
                <div class="text-center contact-info-container">
                    <p>You have confirmed that the name of your organisation is:</p>
                    <h2>"<span class="org-name">{{ session('org_name') }}</span>"</h2>
                    <p>The admin for this organisation is: <span>{{ session('admin_name') }}</span></p>
                    <p>Fill out the form below and we will send your message to them letting them know to get in touch with you.</p>
                </div>
            @endif

            @if($template == 'no-secondary-contact-support')
                <div class="text-center contact-info-container">
                    <p>To recover an administrator account, please contact AidStream support using the form below:</p>
                </div>
            @endif
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-danger">{{ session('error_message') }}</div>
                        {{ Form::open(['method' => 'post', 'id' => 'form-contact']) }}
                        <div class="login-form-group">
                            {!! AsForm::text(['name' => 'full_name', 'label' => 'Your Full Name','required' => true]) !!}
                            {!! AsForm::email(['name' => 'email', 'label' => 'Your E-mail Address', 'required' => true]) !!}
                            {!! AsForm::textarea(['name' => 'message', 'label' => 'Your Message','required' => true]) !!}
                        </div>
                        {{ Form::button('Submit', ['class' => 'btn btn-primary btn-submit btn-form-default', 'type' => 'submit']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
<!-- Scripts -->
@if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
@else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
<script type="text/javascript" src="{{url('/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/additional-methods.js')}}"></script>
<script type="text/javascript" src="{{url('/js/contact.js')}}"></script>
<script>
    $(document).ready(function () {
        Contact.load();
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }
        hamburgerMenu();
    });
</script>
</body>
</html>
