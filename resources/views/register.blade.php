<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ILMS | Register</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <h3>Integrated Library Management System</h3>
        </div>

        <div class="register-box-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span>{{ session()->get('message') }}</span>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span>{{ session()->get('error') }}</span>
                </div>
            @endif

            <p class="login-box-msg">Registration Form</p>

            <form action="{{ route('register') }}" method="POST" autocomplete="off">
                @csrf

                <div class="form-group has-feedback">
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Last name *">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('last_name') }}</span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="First name *">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('first_name') }}</span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-control" placeholder="Middle name">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('middle_name') }}</span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email *">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password *">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Retype password *">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                </div>
            </form>

            <hr class="divider">

            <div class="social-auth-links text-center">
                <a href="{{ route('login.index') }}" class="btn btn-block btn-default btn-flat"><i class="fa fa-user"></i> Login</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>
