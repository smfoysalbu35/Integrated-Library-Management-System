<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Top Borrowed Book Report</title>

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
                <h4 class="text-center">Top Borrowed Book Report</h4>
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
                            <th class="text-center">Book Title</th>
                            <th class="text-center">Call Number</th>
                            <th class="text-center">ISBN</th>
                            <th class="text-center">No. Of Borrow</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topBorrowedBooks as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->call_number }}</td>
                                <td>{{ $book->isbn }}</td>
                                <td>{{ $book->no_of_borrow }}</td>
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
