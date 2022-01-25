@extends('admin.layouts.default-layout')

@section('title', 'Unpaid Penalty Report')

@section('content-header')
    <h1>Unpaid Penalty</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.unpaid-penalties.index') }}">Unpaid Penalty Report</a></li>
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
                    <h3 class="box-title">Unpaid Penalty Report</h3>
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
                                <table id="unpaidPenaltiesTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Patron No.</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Accession No.</th>
                                            <th class="text-center">Book Title</th>

                                            <th class="text-center">Borrow Date</th>
                                            <th class="text-center">Return Date</th>
                                            <th class="text-center">Due Date</th>

                                            <th class="text-center">Overdue (Days)</th>
                                            <th class="text-center">Penalty Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unpaidPenaltiesTableBody">

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
                    url : '/report/unpaid-penalties',
                    data : {'_token' : CSRF_TOKEN, 'from' : from, 'to' : to},
                    success : (response) => {
                        if(response.data.length > 0) {
                            $('#btnPrint').removeClass('invisible')

                            let unpaidPenaltiesTable = ''
                            response.data.forEach(penalty => {
                                unpaidPenaltiesTable += `
                                    <tr>
                                        <td>${penalty.patron.patron_no}</td>
                                        <td>${penalty.patron.name}</td>
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
                            $('#unpaidPenaltiesTableBody').html(unpaidPenaltiesTable)
                        }else {
                            $('#btnPrint').addClass('invisible')
                            $('#unpaidPenaltiesTableBody').html('<tr><td colspan="9"><h5>No Unpaid Penalty Report Found</h5></td></tr>')
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
                window.open(`/report/unpaid-penalties/print?from=${from}&to=${to}`)
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#unpaidPenaltiesTableBody').html('<tr><td colspan="9"><h5></h5></td></tr>')
        }
    </script>
@endpush
