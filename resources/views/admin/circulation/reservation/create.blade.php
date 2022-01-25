@extends('admin.layouts.default-layout')

@section('title', 'Reserve Book')

@section('content-header')
    @if(auth()->guard()->user()->user_type === 1)
        <a href="{{ route('reservations.index') }}" class="btn btn-primary"><i class="fa fa-reply-all"></i> Back</a>
        <ol class="breadcrumb">
            <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
            <li class="active"><a href="{{ route('reservations.index') }}">Reservation</a></li>
            <li class="active"><a href="{{ route('reservations.create') }}">Reserve Book</a></li>
        </ol>
    @else
        <h1>Manage Reservation</h1>
        <ol class="breadcrumb">
            <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
            <li class="active"><a href="#">Reservation</a></li>
            <li class="active"><a href="{{ route('reservations.create') }}">Reserve Book</a></li>
        </ol>
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reserve Book</h3>
                    <button type="button" name="btnClear" id="btnClear" class="btn btn-default btn-sm pull-right">Clear</button>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="patronNo">Enter Patron No.</label>
                                    <div class="input-group">
                                        <input type="text" name="patronNo" id="patronNo" class="form-control" autocomplete="off" autofocus>
                                        <div class="input-group-btn">
                                            <button type="button" name="btnScan" id="btnScan" class="btn btn-default">Scan</button>
                                        </div>
                                    </div>
                                    <span id="patronNoError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
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

        $(document).ready(function() {
            $('#btnScan').click(function() {
                clear()
                $(this).attr({disabled : true})

                let patronNo = $('#patronNo').val()

                $.ajax({
                    type : 'POST',
                    url : '/reservations/patron-record',
                    data : {'_token' : CSRF_TOKEN, 'patron_no' : patronNo},
                    success : (response) => {
                        $(this).attr({disabled : false})

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
                    },
                    error : (error) => {
                        $(this).attr({disabled : false})

                        if(error.status === 422) {
                            $('#patronNoError').html(error.responseJSON.errors.patron_no)
                            $('#patronNo').select()
                        }

                        if(error.status === 500) {
                            errorNotification(error.responseJSON.message)
                        }
                    }
                })
            });

            $('#patronNo').blur(function() {
                $('#btnScan').trigger('click')
            });

            $('#btnReserve').click(function() {
                $('.text-danger').html('')
                $(this).attr({disabled : true})

                let accessionNo = $('#accessionNo').val()
                let patronNo = $('#patronNo').val()

                $.ajax({
                    type : 'POST',
                    url : '/reservations',
                    data : {'_token' : CSRF_TOKEN, 'accession_no' : accessionNo, 'patron_no' : patronNo},
                    success : (response) => {
                        successNotification(response.message)
                        $(this).attr({disabled : false})
                        $('#btnScan').trigger('click')
                        $('#accessionNo').select()
                    },
                    error : (error) => {
                        $(this).attr({disabled : false})

                        if(error.status === 400) {
                            warningNotification(error.responseJSON.message)
                            $('#accessionNo').select()
                        }

                        if(error.status === 422) {
                            $('#accessionNoError').html(error.responseJSON.errors.accession_no)
                            $('#patronNoError').html(error.responseJSON.errors.patron_no)
                        }

                        if(error.status === 500) {
                            errorNotification(err.responseJSON.message)
                        }
                    }
                })
            });

            $('#btnClear').click(function() {
                clear()
                $('.form-control').val('')
                $('#btnScan').attr({disabled : false})
                $('#btnReserve').attr({disabled : false})
                $('#patronNo').select()
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#reservationsTableBody').html('<tr><td colspan="4"><h5></h5></td></tr>')
        }
    </script>
@endpush
