<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Accession Record Report</title>

    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center"><b>Library Management System</b></h4>
                <h4 class="text-center">Accession Record Report</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">Accession No.</th>
                            <th class="text-center">Book Title</th>
                            <th class="text-center">Call Number</th>
                            <th class="text-center">ISBN</th>

                            <th class="text-center">Acquired Date</th>
                            <th class="text-center">Donnor Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Book Shelf/Location Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accessions as $accession)
                            <tr>
                                <td>{{ $accession->accession_no }}</td>
                                <td>{{ $accession->book->title }}</td>
                                <td>{{ $accession->book->call_number }}</td>
                                <td>{{ $accession->book->isbn }}</td>

                                <td>{{ $accession->acquired_date }}</td>
                                <td>{{ $accession->donnor_name }}</td>
                                <td>{{ $accession->price }}</td>
                                <td>{{ $accession->location->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5 class="text-center">********** WELCOME **********</h5>
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
