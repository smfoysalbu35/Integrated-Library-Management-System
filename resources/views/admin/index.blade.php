@extends('admin.layouts.dashboard')

@section('title', 'Dashboard')

@section('content-header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    @if(auth()->guard()->user()->user_type === 1)
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua">
                        <i class="fa fa-user"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">No. Of User</span>
                        <span class="info-box-number">{{ $userCount }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green">
                        <i class="fa fa-user"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">No. Of Patron</span>
                        <span class="info-box-number">{{ $patronCount }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua">
                        <i class="fa fa-book"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">No. Of Book</span>
                        <span class="info-box-number">{{ $bookCount }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green">
                        <i class="fa fa-book"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">No. Of Accession</span>
                        <span class="info-box-number">{{ $accessionCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Borrow Transaction</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Borrow Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($borrows as $borrow)
                                    <tr>
                                        <td>{{ $borrow->patron->patron_no }}</td>
                                        <td>{{ $borrow->patron->first_name . ' ' . $borrow->patron->last_name }}</td>
                                        <td>{{ $borrow->accession->accession_no }}</td>
                                        <td>{{ $borrow->accession->book->title }}</td>
                                        <td>{{ $borrow->borrow_date }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"><h5>No Borrow Transaction</h5></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Return Book Transaction</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Borrow Date</th>
                                    <th class="text-center">Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returnBooks as $returnBook)
                                    <tr>
                                        <td>{{ $returnBook->patron->patron_no }}</td>
                                        <td>{{ $returnBook->patron->first_name . ' ' . $returnBook->patron->last_name }}</td>
                                        <td>{{ $returnBook->accession->accession_no }}</td>
                                        <td>{{ $returnBook->accession->book->title }}</td>
                                        <td>{{ $returnBook->borrow->borrow_date }}</td>
                                        <td>{{ $returnBook->return_date }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"><h5>No Return Book Transaction</h5></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Reservation Transaction</h3>
                </div>

                <div class="box-body">
                    <table class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Reservation Date</th>
                                    <th class="text-center">Reservation End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->patron->patron_no }}</td>
                                        <td>{{ $reservation->patron->first_name . ' ' . $reservation->patron->last_name }}</td>
                                        <td>{{ $reservation->accession->accession_no }}</td>
                                        <td>{{ $reservation->accession->book->title }}</td>
                                        <td>{{ $reservation->reservation_date }}</td>
                                        <td>{{ $reservation->reservation_end_date }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"><h5>No Reservation Transaction</h5></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Payment Transaction</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Transaction Date</th>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Total Penalty</th>
                                    <th class="text-center">Payment</th>
                                    <th class="text-center">Change</th>
                                    <th class="text-center">Transact by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date }}</td>
                                        <td>{{ $transaction->patron->patron_no }}</td>
                                        <td>{{ $transaction->patron->first_name . ' ' . $transaction->patron->last_name }}</td>
                                        <td>{{ $transaction->total_penalty }}</td>
                                        <td>{{ $transaction->payment }}</td>
                                        <td>{{ $transaction->change }}</td>
                                        <td>{{ $transaction->user->first_name . ' ' . $transaction->user->last_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"><h5>No Payment Transaction</h5></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
