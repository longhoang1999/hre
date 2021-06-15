<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đăng nhập</title>
	<link rel="shortcut icon" href="{{asset('imgs/icon.png')}}" type="image/x-icon">
	<link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/login.css')}}">
</head>
<body>
	<div class="form-login">
		<h4 class="forn-weight-bold text-info">Đăng nhập</h4>
		<form method="post" action="{{route('user.postLogin')}}">
			@csrf
			@if(Session::has("error"))
				<div class="alert alert-danger">
					<p class="m-0">{{Session::get("error")}}</p>
				</div>
			@endif
			@if (count($errors) > 0)
	            <div class="alert alert-danger">
	                <ul>
	                    @foreach ($errors->all() as $error)
	                        <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>
	        @endif
			<div class="form-group">
			    <label for="inputUser">Username</label>
			    <input type="text" class="form-control" id="inputUser" placeholder="Enter Username" required="" name="username">
			</div>
			<div class="form-group">
			    <label for="inputPassword">Password</label>
			    <input type="password" class="form-control" id="inputPassword" placeholder="Enter Password" required="" name="password" minlength="8" maxlength="32">
			</div>
			<button type="submit" class="btn btn-primary">Đăng nhập</button>
		</form>
	</div>

	<script type="text/javascript" src="{{asset('js/jquery-3.5.1.js')}}"></script>
	<script type="text/javascript" src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
</body>
</html>