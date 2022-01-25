@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Library Clearance')

@section('content-header')
    <h1>Library Clearance</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.library-clearance.index') }}">Library Clearance</a></li>
    </ol>
@endsection

@section('modals')
    <div class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Library Clearance</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="transactionDate">Transaction Date</label>
                                                <span id="transactionDate" class="form-control"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="patronNo">Patron No.</label>
                                                <span id="patronNo" class="form-control"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="patronName">Patron Name</label>
                                                <span id="patronName" class="form-control"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="transactBy">Transact by</label>
                                                <span id="transactBy" class="form-control"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="penaltiesTable" class="table table-bordered table-striped table-hover text-center">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Accession No.</th>
                                                        <th class="text-center">Book Title</th>
                                                        <th class="text-center">Penalty</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="penaltiesTableBody">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="totalPenalty">Total Penalty</label>
                                                <span id="totalPenalty" class="form-control"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment">Payment</label>
                                                <span id="payment" class="form-control"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="change">Change</label>
                                                <span id="change" class="form-control"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Library Clearance</h3>
                </div>
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
                                <table id="transactionsTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Transaction Date</th>
                                        <th class="text-center">Patron No.</th>
                                        <th class="text-center">Name</th>

                                        <th class="text-center">Total Penalty</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center">Change</th>

                                        <th class="text-center">Transact by</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="transactionsTableBody">

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
            $('#btnGenerate').click(function() {
                clear()

                let from = $('#from').val()
                let to = $('#to').val()

                $.ajax({
                    type : 'POST',
                    url : '/report/transactions',
                    data : {'_token' : CSRF_TOKEN, 'from' : from, 'to' : to},
                    success : (response) => {
                        if(response.data.length > 0) {
                            $('#btnPrint').removeClass('invisible')

                            let transactionsTable = ''
                            response.data.forEach(transaction => {
                                transactionsTable += `
                                    <tr>
                                        <td>${transaction.transaction_date}</td>
                                        <td>${transaction.patron.patron_no}</td>
                                        <td>${transaction.patron.name}</td>

                                        <td>${transaction.total_penalty}</td>
                                        <td>${transaction.payment}</td>
                                        <td>${transaction.change}</td>

                                        <td>${transaction.user.name}</td>
                                        <td colspan="2">
                                            <button type="button" onclick="viewDetails(${transaction.id})" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View Details</button>
                                            <a href="/report/transactions/${transaction.id}/print-details" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print Details</a>
                                        </td>
                                    </tr>
                                `
                            })
                            $('#transactionsTableBody').html(transactionsTable)
                        }else {
                            $('#btnPrint').addClass('invisible')
                            $('#transactionsTableBody').html('<tr><td colspan="8"><h5>No Transaction Report Found</h5></td></tr>')
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
                window.open(`/report/transactions/print?from=${from}&to=${to}`)
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#transactionsTableBody').html('<tr><td colspan="8"><h5></h5></td></tr>')
        }

        function viewDetails(id) {
            $.get(`/report/transactions/${id}`, (response) => {
                $('.modal').modal('show')
                $('.form-control').html('')
                $('#penaltiesTableBody').html('<tr><td colspan="3"><h5>No Penalty Record</h5></td></tr>')

                $('#transactionDate').html(response.data.transaction_date)
                $('#patronNo').html(response.data.patron.patron_no)
                $('#patronName').html(response.data.patron.name)
                $('#transactBy').html(response.data.user.name)

                let penaltiesTable = ''
                if(response.data.transaction_details.length > 0) {
                    response.data.transaction_details.forEach(transactionDetail => {
                        penaltiesTable += `
                            <tr>
                                <td>${transactionDetail.accession.accession_no}</td>
                                <td>${transactionDetail.accession.book.title}</td>
                                <td>${transactionDetail.penalty.amount}</td>
                            </tr>
                        `
                    })
                    $('#penaltiesTableBody').html(penaltiesTable)
                }

                $('#totalPenalty').html(response.data.total_penalty)
                $('#payment').html(response.data.payment)
                $('#change').html(response.data.change)
            });
        }
    </script>
@endpush
