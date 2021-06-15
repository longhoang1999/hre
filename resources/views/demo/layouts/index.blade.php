<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <link rel="shortcut icon" href="{{asset('imgs/icon.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <script type="text/javascript" src="{{asset('js/jquery-3.5.1.js')}}"></script>
    <script type="text/javascript" src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    @yield('header_styles')

    <title>
        @section('title')
            | DEMO
        @show
    </title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('user.demo')}}">
                        Search <span class="sr-only">(current)</span>
                    </a>
                </li>
                @if(Auth::check())
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('user.makeWord')}}">
                        FormCreate <span class="sr-only">(current)</span>
                    </a>
                </li>
                @endif
                @if(Auth::check())
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('user.logout')}}">
                        Logout <span class="sr-only">(current)</span>
                    </a>
                </li>
                @else
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('user.login')}}">
                        Login <span class="sr-only">(current)</span>
                    </a>
                </li>
                @endif
                @if(!Auth::check())
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('user.create')}}">
                        Registration <span class="sr-only">(current)</span>
                    </a>
                </li>
                @endif
                
            </ul>
        </div>
    </nav>

    <div class="body">
        @yield('content')
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('footer-js')
    
</body>
</html>