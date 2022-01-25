<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patron Attendance Monitoring Report</title>

    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center"><b>Library Management System</b></h4>
                <h4 class="text-center">Patron Attendance Monitoring Report</h4>
                <h4 class="text-center">From <b>{{ $from }}</b> To <b>{{ $to }}</b></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">Patron No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date In</th>
                            <th class="text-center">Time In</th>
                            <th class="text-center">Date Out</th>
                            <th class="text-center">Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patronAttendanceLogs as $patronAttendanceLog)
                            <tr>
                                <td>{{ $patronAttendanceLog->patron->patron_no }}</td>
                                <td>{{ $patronAttendanceLog->patron->first_name . ' ' . $patronAttendanceLog->patron->last_name }}</td>
                                <td>{{ $patronAttendanceLog->date_in }}</td>
                                <td>{{ $patronAttendanceLog->time_in }}</td>
                                <td>{{ $patronAttendanceLog->date_out }}</td>
                                <td>{{ $patronAttendanceLog->time_out }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5 class="text-center">********** Nothing to Follow **********</h5>
            </div>
        </div>

        <footer>
            <p>Printed by: <b>{{ auth()->guard()->user()->first_name . ' ' . auth()->guard()->user()->last_name }}</b> | Date Printed: <b>{{ date('Y-m-d') }}</b> | Time Printed: <b>{{ date('H:i') }}</b></p>
        </footer>
    </div>

    <script>
        setTimeout(() => {
            window.print();
        }, 100);
    </script>
</body>
</html>
