<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Library Holding Report</title>

    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center"><b>Library Management System</b></h4>
            <h4 class="text-center">Library Holding Report</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                <tr>
                    <th class="text-center">Book Title</th>
                    <th class="text-center">Call Number</th>
                    <th class="text-center">ISBN</th>

                    <th class="text-center">Book Edition</th>
                    <th class="text-center">Book Volume</th>

                    <th class="text-center">Publisher</th>
                    <th class="text-center">Place Publication</th>
                    <th class="text-center">Copy Right</th>
                    <th class="text-center">Book Copy</th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->call_number }}</td>
                        <td>{{ $book->isbn }}</td>

                        <td>{{ $book->edition }}</td>
                        <td>{{ $book->volume }}</td>

                        <td>{{ $book->publisher }}</td>
                        <td>{{ $book->place_publication }}</td>
                        <td>{{ $book->copy_right }}</td>
                        <td>{{ $book->copy }}</td>
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
