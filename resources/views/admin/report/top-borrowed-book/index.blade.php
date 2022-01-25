@extends('admin.layouts.default-layout')

@section('title', 'Top Borrowed Book Report')

@section('content-header')
    <h1>Top Borrowed Book</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.top-borrowed-books.index') }}">Top Borrowed Book Report</a></li>
    </ol>
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <!-- col-md-12 -->
        <div class="col-md-12">
            <!-- box box-primary -->
            <div class="box box-primary">
                <!-- box-header -->
                <div class="box-header with-border">
                    <h3 class="box-title">Top Borrowed Book Report</h3>
                </div>
                <!-- box-header -->

                <!-- box-body -->
                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="from">From*</label>
                                <input type="date" name="from" id="from" class="form-control">
                                <span id="fromError" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="to">To*</label>
                                <input type="date" name="to" id="to" class="form-control">
                                <span id="toError" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="btn-group">
                                <label for="btnGenerate" class="invisible">Generate</label>
                                <button type="button" name="btnGenerate" id="btnGenerate" class="btn btn-primary">Generate</button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="btn-group">
                                <label for="btnPrint" class="invisible">Print All</label>
                                <button type="button" name="btnPrint" id="btnPrint" class="btn btn-default invisible"><i class="fa fa-print"></i> Print All</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="topBorrowedBooksTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Book Title</th>
                                            <th class="text-center">Call Number</th>
                                            <th class="text-center">ISBN</th>
                                            <th class="text-center">No. Of Borrow</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topBorrowedBooksTableBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- box-body -->
            </div>
            <!-- box box-primary -->
        </div>
        <!-- col-md-12 -->
    </div>
    <!-- row -->
@endsection

@push('scripts')
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function() {
            $('#btnGenerate').click(function() {
                clear()

                let from = $('#from').val()
                let to = $('#to').val()

                $.ajax({
                    type : 'POST',
                    url : '/report/top-borrowed-books',
                    data : {'_token' : CSRF_TOKEN, 'from' : from, 'to' : to},
                    success : (response) => {
                        if(response.topBorrowedBooks.length > 0) {
                            $('#btnPrint').removeClass('invisible')

                            let topBorrowedBooksTable = ''
                            response.topBorrowedBooks.forEach(book => {
                                topBorrowedBooksTable += `
                                    <tr>
                                        <td>${book.title}</td>
                                        <td>${book.call_number}</td>
                                        <td>${book.isbn}</td>
                                        <td>${book.no_of_borrow}</td>
                                    </tr>
                                `
                            })
                            $('#topBorrowedBooksTableBody').html(topBorrowedBooksTable)
                        }else {
                            $('#btnPrint').addClass('invisible')
                            $('#topBorrowedBooksTableBody').html('<tr><td colspan="4"><h5>No Top Borrowed Book Report Found</h5></td></tr>')
                        }
                    },
                    error : (error) => {
                        $('#btnPrint').addClass('invisible')

                        if(error.status === 422) {
                            $('#fromError').html(error.responseJSON.errors.from)
                            $('#toError').html(error.responseJSON.errors.to)
                        }

                        if(error.status === 500) {
                            errorNotification(error.responseJSON.message)
                        }
                    }
                })
            });

            $('#btnPrint').click(function() {
                let from = $('#from').val()
                let to = $('#to').val()
                window.open(`/report/top-borrowed-books/print?from=${from}&to=${to}`)
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#topBorrowedBooksTableBody').html('<tr><td colspan="4"><h5></h5></td></tr>')
        }
    </script>
@endpush
