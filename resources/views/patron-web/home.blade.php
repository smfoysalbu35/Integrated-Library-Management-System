@extends('patron-web.layouts.default-layout')

@section('title', 'Home')

@section('content-header')
    <h1>Home</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-home"></i> Home</p></li>
        <li class="active"><a href="{{ route('patron-web.home') }}">Reserve Book</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Accession List</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="accessionsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Call Number</th>
                                    <th class="text-center">ISBN</th>
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

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reserve Book</h3>
                    <button type="button" name="btnClear" id="btnClear" class="btn btn-default btn-sm pull-right">Clear</button>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="accessionNo">Enter Accession No.</label>
                            <div class="input-group">
                                <input type="text" name="accessionNo" id="accessionNo" class="form-control" autocomplete="off">
                                <div class="input-group-btn">
                                    <button type="button" name="btnReserve" id="btnReserve" class="btn btn-default">Reserve</button>
                                </div>
                            </div>
                            <span id="accessionNoError" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center">List Of Latest Reserve Book</h4>
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
                                    <tbody id="reservationsTableBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $('#accessionsTable').DataTable();

        $(document).ready(function() {
            $('#btnReserve').click(function() {
                let accessionNo = $('#accessionNo').val()

                $('.text-danger').html('')
                $(this).attr({disabled : true})

                $.ajax({
                    type : 'POST',
                    url : '/patron-web/home/reservations',
                    data : {'_token' : CSRF_TOKEN, 'accession_no' : accessionNo},
                    success : (response) => {
                        successNotification(response.message)
                        window.location.reload()
                    },
                    error : (error) => {
                        $(this).attr({disabled : false})

                        if(error.status === 400) {
                            warningNotification(error.responseJSON.message)
                            $('#accessionNo').select()
                        }

                        if(error.status === 422) {
                            $('#accessionNoError').html(error.responseJSON.errors.accession_no)
                        }

                        if(error.status === 500) {
                            errorNotification(err.responseJSON.message)
                        }
                    }
                })
            });

            $('#btnClear').click(function() {
                $('.form-control').val('')
                $('.text-danger').html('')
                $('#btnReserve').attr({disabled : false})
                $('#accessionNo').select()
            });
        });

        getReservations()
        function getReservations() {
            $.get(`/patron-web/home/reservations`, (response) => {
                let reservationsTable = ''
                if(response.data.length > 0) {
                    response.data.forEach(reservation => {
                        reservationsTable += `
                            <tr>
                                <td>${reservation.accession.accession_no}</td>
                                <td>${reservation.book.title}</td>
                                <td>${reservation.reservation_date}</td>
                                <td>${reservation.reservation_end_date}</td>
                            </tr>
                        `
                    })
                    $('#reservationsTableBody').html(reservationsTable)
                }else {
                    $('#reservationsTableBody').html('<tr><td colspan="4"><h5>No Reservation Record</h5></td></tr>')
                }
            })
        }
    </script>
@endpush
