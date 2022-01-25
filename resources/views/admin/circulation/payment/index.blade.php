@extends('admin.layouts.default-layout')

@section('title', 'Payment')

@section('content-header')
    <h1>Manage Payment</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
        <li class="active"><a href="{{ route('payments.index') }}">Payment</a></li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Payment Transaction</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
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

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="text-center">Penalty Detail</h4>
                                        </div>

                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="totalPenalty">Total Penalty</label>
                                                <input type="number" name="totalPenalty" id="totalPenalty" class="form-control payment-form" disabled>
                                                <span id="totalPenaltyError" class="text-danger"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="payment">Payment</label>
                                                <input type="number" name="payment" id="payment" class="form-control payment-form" disabled>
                                                <span id="paymentError" class="text-danger"></span>
                                            </div>

                                            <div class="form-group">
                                                <label for="change">Change</label>
                                                <input type="number" name="change" id="change" class="form-control payment-form" disabled>
                                                <span id="changeError" class="text-danger"></span>
                                            </div>

                                            <div class="btn-group pull-right">
                                                <button type="button" name="btnSettlePayment" id="btnSettlePayment" class="btn btn-default btn-sm" disabled>Settle Payment</button>
                                            </div>
                                        </div>
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
                    url : '/penalties/patron-record',
                    data : {'_token' : CSRF_TOKEN, 'patron_no' : patronNo},
                    success : (response) => {
                        $(this).attr({disabled : false})

                        if(response.penalties.length > 0) {
                            let penaltiesTable = ''
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

                            $('#totalPenalty').val(response.total_penalty)
                            $('#payment').attr({disabled : false})
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

            $('#payment').keyup(function() {
                let totalPenalty = $('#totalPenalty').val()
                let parseTotalPenalty = parseInt(totalPenalty)

                let payment = $('#payment').val()
                let parsePayment = parseInt(payment)

                if(parsePayment >= parseTotalPenalty) {
                    let change = parsePayment - parseTotalPenalty
                    $('#change').val(change)

                    $('#paymentError').html('')
                    $('#btnSettlePayment').attr({disabled : false})
                }else {
                    $('#change').val('')
                    $('#paymentError').html('Please enter atleast exact amount.')
                    $('#btnSettlePayment').attr({disabled : true})
                }
            });

            $('#btnSettlePayment').click(function() {
                if(confirm('Are you sure you want to pay the penalty?')) {
                    $('.text-danger').html('')
                    $('.payment-form').attr({disabled : true})
                    $(this).attr({disabled : true})

                    let patronNo = $('#patronNo').val()
                    let payment = $('#payment').val()
                    let parsePayment = parseInt(payment)

                    $.ajax({
                        type : 'POST',
                        url : '/payments',
                        data : {'_token' : CSRF_TOKEN, 'patron_no' : patronNo, 'payment' : parsePayment},
                        success : (response) => {
                            successNotification(response.message)
                            clear()
                            $('#patronNo').val('')
                        },
                        error : (error) => {
                            $(this).attr({disabled : false})

                            if(error.status === 400) {
                                warningNotification(error.responseJSON.message)
                                $('#payment').select()
                            }

                            if(error.status === 422) {
                                $('#patronNoError').html(error.responseJSON.errors.patron_no)
                                $('#paymentError').html(error.responseJSON.errors.payment)
                            }

                            if(error.status === 500) {
                                errorNotification(err.responseJSON.message)
                            }
                        }
                    })
                }
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('.payment-form').val('')
            $('.payment-form').attr({disabled : true})
            $('#btnSettlePayment').attr({disabled : true})
            $('#penaltiesTableBody').html('<tr><td colspan="7"><h5></h5></td></tr>')
        }
    </script>
@endpush
