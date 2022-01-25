<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ILMS | OPAC</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/skins/skin-blue.min.css') }}">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ route('login.index') }}" class="navbar-brand"><b>Library Management System</b></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="{{ route('patron-attendance-monitoring.index') }}">Attendance Monitoring</a></li>
                            <li class="active"><a href="{{ route('opac.index') }}">OPAC</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="content-wrapper">
            <div class="container">
                <section class="content-header">
                    <h1>Online Public Access Catalog</h1>
                    <ol class="breadcrumb">
                        <li class="active"><a href="{{ route('opac.index') }}"><i class="fa fa-search"></i> OPAC</a></li>
                    </ol>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Accession List</h3>
                                </div>

                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table id="opacTable" class="table table-bordered table-striped table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Accession No.</th>
                                                    <th class="text-center">Book Title</th>
                                                    <th class="text-center">Call Number</th>
                                                    <th class="text-center">ISBN</th>
                                                    <th class="text-center">Book Edition</th>
                                                    <th class="text-center">Book Volume</th>
                                                    <th class="text-center">Book Shelf/Location Name</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($accessions as $accession)
                                                    <tr>
                                                        <td>{{ $accession->accession_no }}</td>
                                                        <td>{{ $accession->book->title }}</td>
                                                        <td>{{ $accession->book->call_number }}</td>
                                                        <td>{{ $accession->book->isbn }}</td>
                                                        <td>{{ $accession->book->edition }}</td>
                                                        <td>{{ $accession->book->volume }}</td>
                                                        <td>{{ $accession->location->name }}</td>
                                                        <td>
                                                            @if($accession->status === 1)
                                                                <span class="label label-success">Available</span>
                                                            @else
                                                                <span class="label label-danger">Not Available</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Developed by</b> <strong>MD. FOYSAL SHEIKH</strong>
                </div>
                <strong>Integrated Library Management System</strong>
            </div>
        </footer>
    </div>

    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
    <script>
        $('#opacTable').DataTable();
    </script>
</body>
</html>
