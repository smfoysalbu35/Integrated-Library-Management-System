<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Top Library User Report</title>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- col-md-12 -->
            <div class="col-md-12">
                <h4 class="text-center"><b>Library Management System</b></h4>
                <h4 class="text-center">Top {{ $patronType->name }} Library User Report</h4>
                <h4 class="text-center">From <b>{{ $from }}</b> To <b>{{ $to }}</b></h4>
            </div>
            <!-- col-md-12 -->
        </div>
        <!-- row -->

        <!-- row -->
        <div class="row">
            <!-- col-md-12 -->
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">Patron No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">No. Of Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topLibraryUsers as $patron)
                            <tr>
                                <td>{{ $patron->patron_no }}</td>
                                <td>{{ $patron->first_name . ' ' . $patron->last_name }}</td>
                                <td>{{ $patron->section->name }}</td>
                                <td>{{ $patron->no_of_attendance }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- col-md-12 -->
        </div>
        <!-- row -->

        <!-- row -->
        <div class="row">
            <!-- col-md-12 -->
            <div class="col-md-12">
                <h5 class="text-center">********** Nothing to Follow **********</h5>
            </div>
            <!-- col-md-12 -->
        </div>
        <!-- row -->

        <!-- footer -->
        <footer>
            <p>Printed by: <b>{{ auth()->guard()->user()->first_name . ' ' . auth()->guard()->user()->last_name }}</b> | Date Printed: <b>{{ date('Y-m-d') }}</b> | Time Printed: <b>{{ date('H:i') }}</b></p>
        </footer>
        <!-- footer -->
    </div>
    <!-- container -->

    <script>
        setTimeout(() => {
            window.print();
        }, 100);
    </script>
</body>
</html>
