@extends('admin.layouts.default-layout')

@section('title', 'Top Library User Report')

@section('content-header')
    <h1>Top Library User</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.top-library-users.index') }}">Top Library User Report</a></li>
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
                    <h3 class="box-title">Top Library User Report</h3>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="patronType">Patron Type*</label>
                                <select name="patronType" id="patronType" class="form-control select2" style="width: 100%;">
                                    @foreach($patronTypes as $patronType)
                                        <option value="{{ $patronType->id }}">{{ $patronType->name }}</option>
                                    @endforeach
                                </select>
                                <span id="patronTypeError" class="text-danger"></span>
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
                                <table id="topLibraryUsersTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Patron No.</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Section</th>
                                            <th class="text-center">No. Of Attendance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topLibraryUsersTableBody">

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
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
            });

            $('#btnGenerate').click(function() {
                clear()

                let from = $('#from').val()
                let to = $('#to').val()
                let patronTypeId = $('#patronType').val()

                $.ajax({
                    type : 'POST',
                    url : '/report/top-library-users',
                    data : {'_token' : CSRF_TOKEN, 'from' : from, 'to' : to, 'patron_type_id' : patronTypeId},
                    success : (response) => {
                        if(response.topLibraryUsers.length > 0) {
                            $('#btnPrint').removeClass('invisible')

                            let topLibraryUsersTable = ''
                            response.topLibraryUsers.forEach(patron => {
                                topLibraryUsersTable += `
                                    <tr>
                                        <td>${patron.patron_no}</td>
                                        <td>${patron.first_name} ${patron.last_name}</td>
                                        <td>${patron.section.name}</td>
                                        <td>${patron.no_of_attendance}</td>
                                    </tr>
                                `
                            })
                            $('#topLibraryUsersTableBody').html(topLibraryUsersTable)
                        }else {
                            $('#btnPrint').addClass('invisible')
                            $('#topLibraryUsersTableBody').html('<tr><td colspan="4"><h5>No Top Library User Report Found</h5></td></tr>')
                        }
                    },
                    error : (error) => {
                        $('#btnPrint').addClass('invisible')

                        if(error.status === 422) {
                            $('#fromError').html(error.responseJSON.errors.from)
                            $('#toError').html(error.responseJSON.errors.to)
                            $('#patronTypeError').html(error.responseJSON.errors.patron_type_id)
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
                let patronTypeId = $('#patronType').val()
                window.open(`/report/top-library-users/print?from=${from}&to=${to}&patron_type_id=${patronTypeId}`)
            });
        });

        clear()
        function clear() {
            $('.text-danger').html('')
            $('#topLibraryUsersTableBody').html('<tr><td colspan="4"><h5></h5></td></tr>')
        }
    </script>
@endpush
