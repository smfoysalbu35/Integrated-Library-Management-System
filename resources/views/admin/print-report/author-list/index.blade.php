<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Author List Report</title>

    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center"><b>Integrated Library Management System</b></h4>
                <h4 class="text-center">Author List Report</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Author Name</th>
                            <th class="text-center">Book(s)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authors as $author)
                            <tr>
                                <td class="text-center">{{ $author->name }}</td>
                                <td>
                                    <ul>
                                        @forelse($author->book_authors as $bookAuthor)
                                            <li>{{ $bookAuthor->book->title }}</li>
                                        @empty
                                            <b>No Book Record</b>
                                        @endforelse
                                    </ul>
                                </td>
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
