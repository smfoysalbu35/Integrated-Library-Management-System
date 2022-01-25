@extends('admin.layouts.default-layout')

@section('title', 'Return Book')

@section('content-header')
    @if(auth()->guard()->user()->user_type === 1)
        <a href="{{ route('return-books.index') }}" class="btn btn-primary"><i class="fa fa-reply-all"></i> Back</a>
        <ol class="breadcrumb">
            <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
            <li class="active"><a href="{{ route('return-books.index') }}">Return</a></li>
            <li class="active"><a href="{{ route('return-books.create') }}">Return Book</a></li>
        </ol>
    @else
        <h1>Manage Return Book</h1>
        <ol class="breadcrumb">
            <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
            <li class="active"><a href="#">Return</a></li>
            <li class="active"><a href="{{ route('return-books.create') }}">Return Book</a></li>
        </ol>
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Return Book</h3>
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

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center">List Of Borrowed Book</h4>
                                    <div class="table-responsive">
                                        <table id="borrowsTable" class="table table-bordered table-striped table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Accession No.</th>
                                                    <th class="text-center">Book Title</th>
                                                    <th class="text-center">Borrow Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="borrowsTableBody">

                                            </tbody>
                                        </table>
                                    </div>
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
                                            <button type="button" name="btnReturn" id="btnReturn" class="btn btn-default">Return</button>
                                        </div>
                                    </div>
                                    <span id="accessionNoError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center">List Of Penalty</h4>
                                    <div class="table-responsive">
                                        <table id="penaltiesTable" class="table table-bordered table-striped table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Accession No.</th>
                                                    <th class="text-center">Book Title</th>

                                                    <th class="text-center">Borrow Date</th>
                                                    <th class="text-center">Return Date</th>
                                                    <th class="text-center">Due Date</th>

                                                    <th class="text-center">Overdue (Days)</th>
                                                    <th class="text-center">Penalty Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="penaltiesTableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
                    url : '/borrows/patron-record',
                    data : {'_token' : CSRF_TOKEN, 'patron_no' : patronNo},
                    success : (response) => {
                        $(this).attr({disabled : false})

                        let borrowsTable = ''
                        if(response.borrows.length > 0) {
                            response.borrows.forEach(borrow => {
                                borrowsTable += `
                                    <tr>
                                        <td>${borrow.accession.accession_no}</td>
                                        <td>${borrow.book.title}</td>
                                        <td>${borrow.borrow_date}</td>
                                    </tr>
                                `
                            })
                            $('#borrowsTableBody').html(borrowsTable)
                        }else {
                            $('#borrowsTableBody').html('<tr><td colspan="3"><h5>No Borrow Record</h5></td></tr>')
                        }

                        let penaltiesTable = ''
                        if(response.penalties.length > 0) {
                            response.penalties.forEach(penalty => {
                                penaltiesTable += `
                                    <tr>
                                        <td>${penalty.accession.accession_no}</td>
                                        <td>${penalty.book.title}</td>

                                        <td>${penalty.borrow.borrow_date}</td>
                                        <td>${penalty.return_book.return_date}</td>
                                        <td>${penalty.penalty_due_date}</td>

                                        <td>${penalty.overdue}</td>
                                        <td>${penalty.amount}</td>
                                    </tr>
                                `
                            })
                            $('#penaltiesTableBody').html(penaltiesTable)
                        }else {
                            $('#penaltiesTableBody').html('<tr><td colspan="7"><h5>No Penalty Record</h5></td></tr>')
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

            $('#btnReturn').click(function() {
                $('.text-danger').html('')
                $(this).attr({disabled : true})

                let accessionNo = $('#accessionNo').val()
                let patronNo = $('#patronNo').val()

                $.ajax({
                    type : 'POST',
                    url : '/return-books',
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
                $('#btnReturn').attr({disabled : false})
                $('#patronNo').select()
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#borrowsTableBody').html('<tr><td colspan="3"><h5></h5></td></tr>')
            $('#penaltiesTableBody').html('<tr><td colspan="7"><h5></h5></td></tr>')
        }
    </script>
@endpush
