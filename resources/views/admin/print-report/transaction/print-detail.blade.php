<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Library Clearance Details</title>

    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center"><b>Integrated Library Management System</b></h4>
            <h4 class="text-center">Library Clearance Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>Patron Name: <b>{{ $transaction->patron->first_name . ' ' . $transaction->patron->last_name }}</b></h4>
            <h4>Patron No.: <b>{{ $transaction->patron->patron_no }}</b></h4>
            <h4>Transaction Date: <b>{{ $transaction->transaction_date }}</b></h4>
            <h4>Transact By: <b>{{ $transaction->user->first_name . ' ' . $transaction->user->last_name }}</b></h4>
            <p>We have received all books and other library resources which you borrowed from Barisal University Central Library. </p>
            <p>Thank you very much for using the Barisal University Central Library.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center">Transaction Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                <tr>
                    <th class="text-center">Accession No.</th>
                    <th class="text-center">Book Title</th>
                    <th class="text-center">Penalty</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($transaction->transaction_details as $transactionDetail)
                    <tr>
                        <td>{{ $transactionDetail->accession->accession_no }}</td>
                        <td>{{ $transactionDetail->accession->book->title }}</td>
                        <td>{{ $transactionDetail->penalty->amount}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4>Total Penalty: <b>{{ $transaction->total_penalty }}</b></h4>
            <h4>Payment: <b>{{ $transaction->payment }}</b></h4>
            <h4>Change: <b>{{ $transaction->change }}</b></h4>
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
