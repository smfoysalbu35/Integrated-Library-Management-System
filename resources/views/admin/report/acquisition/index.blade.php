@extends('admin.layouts.default-layout')

@section('title', 'Acquisition Report')

@section('content-header')
    <h1>Acquisition</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.acquisitions.index') }}">Acquisition Report</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Acquisition Report</h3>
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
                                <table id="accessionsTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Accession No.</th>
                                            <th class="text-center">Book Title</th>
                                            <th class="text-center">Call Number</th>
                                            <th class="text-center">ISBN</th>

                                            <th class="text-center">Acquired Date</th>
                                            <th class="text-center">Donnor Name</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Book Shelf/Location Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="accessionsTableBody">

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
                    url : '/report/acquisitions',
                    data : {'_token' : CSRF_TOKEN, 'from' : from, 'to' : to},
                    success : (response) => {
                        if(response.data.length > 0) {
                            $('#btnPrint').removeClass('invisible')

                            let accessionsTable = ''
                            response.data.forEach(accession => {
                                accessionsTable += `
                                    <tr>
                                        <td>${accession.accession_no}</td>
                                        <td>${accession.book.title}</td>
                                        <td>${accession.book.call_number}</td>
                                        <td>${accession.book.isbn}</td>

                                        <td>${accession.acquired_date}</td>
                                        <td>${accession.donnor_name}</td>
                                        <td>${accession.price}</td>
                                        <td>${accession.location.name}</td>
                                    </tr>
                                `
                            })
                            $('#accessionsTableBody').html(accessionsTable)
                        }else {
                            $('#btnPrint').addClass('invisible')
                            $('#accessionsTableBody').html('<tr><td colspan="8"><h5>No Acquisition Report Found</h5></td></tr>')
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
                window.open(`/report/acquisitions/print?from=${from}&to=${to}`)
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#accessionsTableBody').html('<tr><td colspan="8"><h5></h5></td></tr>')
        }
    </script>
@endpush
