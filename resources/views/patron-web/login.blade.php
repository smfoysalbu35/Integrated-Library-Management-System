<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ILMS | Login</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <h3>Integrated Library Management System</h3>
        </div>

        <div class="login-box-body">
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

            <p class="login-box-msg">Patron Account Login Form</p>

            <form action="{{ route('patron-web.login') }}" method="POST" autocomplete="off">
                @csrf

                <div class="form-group has-feedback">
                    <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <div class="form-group has-error">
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>

            <hr class="divider">

            <div class="social-auth-links text-center">
                <a href="{{ route('patron-web.register.index') }}" class="btn btn-block btn-default btn-flat"><i class="fa fa-user-plus"></i> Register</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>
