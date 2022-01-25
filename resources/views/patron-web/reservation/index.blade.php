@extends('patron-web.layouts.default-layout')

@section('title', 'Reservation')

@section('content-header')
    <h1>Reservation Record</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-home"></i> Home</p></li>
        <li class="active"><a href="{{ route('patron-web.reservations.index') }}">Reservation</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reservation List</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="reservationsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Reservation Date</th>
                                    <th class="text-center">Reservation End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->accession->accession_no }}</td>
                                        <td>{{ $reservation->accession->book->title }}</td>
                                        <td>{{ $reservation->reservation_date }}</td>
                                        <td>{{ $reservation->reservation_end_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#reservationsTable').DataTable();
    </script>
@endpush
