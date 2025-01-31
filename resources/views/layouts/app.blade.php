<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                            <a class="nav-link btn" id="userName" data-bs-toggle="dropdown" aria-expanded="false"></a>
                            <ul class="dropdown-menu">
                                <li><button type="submit" class="dropdown-item" onclick="logout()">Logout</button></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

<script>
var cookieVal = document.cookie
        .split('; ')
        .find(row => row.startsWith('bearerToken'))
        .split('=')[1];

var userName = document.cookie
    .split('; ')
    .find(row => row.startsWith('userName'))
    .split('=')[1];

var csrf = $("meta[name='csrf-token']").attr("content");

function logout() {
    $.ajax({
        url: "/api/auth/logout",
        type: "POST",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN':`${csrf}`,
            'Authorization': 'Bearer ' + cookieVal,
            'Accept':'application/json'
        },
        success:function () {
            location.replace('/');
            document.cookie="bearerToken=";
            document.cookie="app_event_session=";
            document.cookie="XSRF-TOKEN=";
            document.cookie="userName=";
        }
    });
}

$("#userName").append(userName);
</script>
</html>
