<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>AidStream - @yield('title', 'No Title')</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <style>
        .view-other-language:hover .hidden {
            display: block!important;
        }

        .dl-horizontal, .activity-element-wrapper {
            border-bottom: 1px solid rgba(151, 151, 151, 0.2);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .dl-horizontal dt, .dl-horizontal .activity-element-label, .activity-element-wrapper dt, .activity-element-wrapper .activity-element-label {
            margin-top: 0;
            font-weight: normal;
            color: rgba(72, 72, 72, 0.7);
            width: 240px;
            text-align: left;
            line-height: 22px;
            white-space: inherit;
        }

        /*.dl-horizontal dd, .dl-horizontal .activity-element-info, .activity-element-wrapper dd, .activity-element-wrapper .activity-element-info {*/
            /*margin-left: 280px;*/
        /*}*/

        .dl-horizontal dd em, .dl-horizontal .activity-element-info em, .activity-element-wrapper dd em, .activity-element-wrapper .activity-element-info em {
            color: rgba(72, 72, 72, 0.7);
        }

        .dl-horizontal dd li, .dl-horizontal .activity-element-info li, .activity-element-wrapper dd li, .activity-element-wrapper .activity-element-info li {
            list-style: none;
        }

        .dl-horizontal dd li:before, .dl-horizontal .activity-element-info li:before, .activity-element-wrapper dd li:before, .activity-element-wrapper .activity-element-info li:before {
            content: "•";
            color: #00A8FF  ;
            margin: 0 5px 0 -10px;
        }

        .dl-horizontal .title, .activity-element-wrapper .title {
            font-family: Open-Sans-Bold;
            color: rgba(72, 72, 72, 0.7);
            margin-bottom: 8px;
        }

        .show-more-info, .hide-more-info {
            font-size: 12px;
            margin-top: 3px;
        }

        .more-info {
            background: #e4f1f7  ;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            position: relative;
            margin-top: 28px;
        }

        .more-info:before {
            position: absolute;
            top: -6px;
            left: 300px;
            content: '';
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 6px solid #e4f1f7  ;
        }

        .more-info dl {
            border-bottom: 1px solid #fff;
            padding: 7px 14px;
        }

        .more-info dl dd {
            margin-left: 245px;
        }

        .activity-element-list {
            margin-bottom: 10px;
            overflow: hidden;
        }

        .activity-element-list + .activity-element-list {
            border-top: 1px dashed #ededed  ;
            padding-top: 8px;
            margin-top: 10px;
        }

        .activity-element-label,.activity-element-info {
            float: left;
        }
        

    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')

</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="navbar-brand">
                <a href="{{ ($loggedInUser && $loggedInUser->role_id == 3) ? url(config('app.super_admin_dashboard')) : config('app.admin_dashboard') }}"
                   alt="Aidstream">Aidstream</a>
            </div>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            @if(auth()->user() && !isSuperAdminRoute())
                <ul class="nav navbar-nav pull-left add-new-activity">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Add a New Activity<span
                                    class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{route('activity.create') }}">Add Activity Manually</a></li>
                            {{--<li><a href="{{route('wizard.activity.create') }}">Add Activity using Wizard</a></li>--}}
                            <li><a href="{{ route('activity-upload.index') }}">Upload Activities</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
            <ul class="nav navbar-nav navbar-right navbar-admin-dropdown">
                @if (auth()->guest())
                    <li><a href="{{ url('/auth/login') }}">@lang('trans.login')</a></li>
                    <li><a href="{{ url('/auth/register') }}">@lang('trans.register')</a></li>
                @else
                    <li>
                        @if((session('role_id') == 3  || session('role_id') == 4) && !isSuperAdminRoute())
                            <span><a href="{{ route('admin.switch-back') }}" class="pull-left">Switch Back</a></span>
                        @endif
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="avatar-img">
                                <img src="{{url('images/avatar.svg')}}" width="36" height="36" alt="{{$loggedInUser->name}}">
                            </span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            @if(!isSuperAdminRoute())
                                <li><a href="{{url('user/profile')}}">@lang('trans.my_profile')</a></li>
                            @endif
                            <li><a href="{{ url('/auth/logout') }}">@lang('trans.logout')</a></li>

                            @include('unwanted')

                            <li class="pull-left width-491">
                                @if(!isSuperAdminRoute())
                                    <span class="width-490"><a href="{{ route('admin.switch-back') }}"
                                                               class="pull-left">Switch Back</a></span>
                                @endif
                            </li>
                            <li class="pull-left width-491">
                                <div class="navbar-left version-wrap width-490">
                                    @if(!isSuperAdminRoute())
                                        <div class="version pull-right {{ (session('version') == 'V201') ? 'old' : 'new' }}">
                                            @if ((session('version') == 'V201'))
                                                <a class="version-text" href="{{route('upgrade-version.index')}}">Update
                                                    available</a>
                                                <span class="old-version">
                                                 <a href="{{route('upgrade-version.index')}}">Upgrade to IATI version
                                                     {{ session('next_version') }}</a>
                                              </span>
                                            @else
                                                <span class="version-text">IATI version {{session('current_version')}}</span>
                                                <span class="new-version">
                                               You’re using latest IATI version
                                             </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <div class="navbar-right version-wrap">
            @if(auth()->user() && !isSuperAdminRoute())
                <div class="version pull-right {{ (session('version') == 'V201') ? 'old' : 'new' }}">
                    @if (session('next_version'))
                        <a class="version-text" href="{{route('upgrade-version.index')}}">Update available</a>
                        <span class="old-version">
                            <a href="{{route('upgrade-version.index')}}">Upgrade to IATI
                                version {{ session('next_version') }} </a>
                      </span>
                    @else
                        <span class="version-text">IATI version {{ session('current_version') }}</span>
                        <span class="new-version">
                   You’re using latest IATI version
                 </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</nav>

@yield('content')
<div class="scroll-top">
    <a href="#" class="scrollup" title="Scroll to top">icon</a>
</div>

<!-- Scripts -->
<script type="text/javascript">
    var dateFields = document.querySelectorAll('form [type="date"]');
    for (var i = 0; i < dateFields.length; i++) {
        dateFields[i].setAttribute('type', 'text');
        dateFields[i].setAttribute('autocomplete', 'off');
        dateFields[i].classList.add('datepicker');
    }
</script>

@if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/modernizr.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.datetimepicker.full.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/script.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/datatable.js')}}"></script>
@else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        $('form select').select2();
    });
</script>
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<!-- End Google Analytics -->
@yield('script')
@yield('foot')

</body>
</html>
