@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Patron Type')

@section('content-header')
    <h1>Manage Patron Type</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-user"></i> Manage Patron</p></li>
        <li class="active"><a href="{{ route('patron-types.index') }}">Patron Type</a></li>
    </ol>
@endsection

@section('modals')
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Patron Type</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Patron Type Name*</label>
                                    <input type="text" name="name" id="name" class="form-control modal-form">
                                    <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fines">Fines*</label>
                                    <input type="number" name="fines" id="fines" class="form-control modal-form">
                                    <span id="finesError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_of_borrow_allowed">No. of Borrow Allowed*</label>
                                    <input type="number" name="no_of_borrow_allowed" id="no_of_borrow_allowed" class="form-control modal-form">
                                    <span id="noOfBorrowAllowedError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_of_day_borrow_allowed">No. of Day Borrow Allowed*</label>
                                    <input type="number" name="no_of_day_borrow_allowed" id="no_of_day_borrow_allowed" class="form-control modal-form">
                                    <span id="noOfDayBorrowAllowedError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_of_reserve_allowed">No. of Reserve Allowed*</label>
                                    <input type="number" name="no_of_reserve_allowed" id="no_of_reserve_allowed" class="form-control modal-form">
                                    <span id="noOfReserveAllowedError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_of_day_reserve_allowed">No. of Day Reserve Allowed*</label>
                                    <input type="number" name="no_of_day_reserve_allowed" id="no_of_day_reserve_allowed" class="form-control modal-form">
                                    <span id="noOfDayReserveAllowedError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary modal-submit-button">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Patron Type</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Patron Type...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="patronTypesTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron Type Name</th>
                                    <th class="text-center">Fines</th>
                                    <th class="text-center">No. of Borrow Allowed</th>
                                    <th class="text-center">No. of Day Borrow Allowed</th>
                                    <th class="text-center">No. of Reserve Allowed</th>
                                    <th class="text-center">No. of Day Reserve Allowed</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="patronTypesTableBody">

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
        $(document).ready(function() {
            $('#btnAddModal').click(function() {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Add Patron Type')
                $('.modal-submit-button').html('Save')
                $('input[name="_method"]').val('POST')
            });

            $('#modalForm').on('submit', function(e) {
                e.preventDefault()

                $('.text-danger').html('')
                let formData = new FormData(this)
                let _method = $('input[name="_method"]').val()
                let url = ''
                let confirmationMessage = ''

                if(_method === 'POST') {
                    url = '/patron-types'
                    confirmationMessage = 'Are you sure you want to add this patron type?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/patron-types/${id}`
                    confirmationMessage = 'Are you sure you want to update this patron type?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getPatronTypes()
                            _method === 'POST' ? $('.modal-form').val('') : $('.modal').modal('hide')
                        },
                        error : (error) => {
                            $('#nameError').html(error.responseJSON.errors.name)
                            $('#finesError').html(error.responseJSON.errors.fines)
                            $('#noOfBorrowAllowedError').html(error.responseJSON.errors.no_of_borrow_allowed)
                            $('#noOfDayBorrowAllowedError').html(error.responseJSON.errors.no_of_day_borrow_allowed)
                            $('#noOfReserveAllowedError').html(error.responseJSON.errors.no_of_reserve_allowed)
                            $('#noOfDayReserveAllowedError').html(error.responseJSON.errors.no_of_day_reserve_allowed)
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/patron-types/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let patronTypesTable = ''
                        response.data.forEach(patronType => {
                            patronTypesTable += `
                                <tr>
                                    <td>${patronType.name}</td>
                                    <td>${patronType.fines}</td>
                                    <td>${patronType.no_of_borrow_allowed}</td>
                                    <td>${patronType.no_of_day_borrow_allowed}</td>
                                    <td>${patronType.no_of_reserve_allowed}</td>
                                    <td>${patronType.no_of_day_reserve_allowed}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editPatronType(${patronType.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deletePatronType(${patronType.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#patronTypesTableBody').html(patronTypesTable)
                    }else {
                        $('#patronTypesTableBody').html('<tr><td colspan="7"><h5>No Patron Type Result</h5></td></tr>')
                    }
                });
            });
        });

        getPatronTypes()
        function getPatronTypes() {
            $.get(`/patron-types/get`, (response) => {
                if(response.data.length > 0) {
                    let patronTypesTable = ''
                    response.data.forEach(patronType => {
                        patronTypesTable += `
                            <tr>
                                <td>${patronType.name}</td>
                                <td>${patronType.fines}</td>
                                <td>${patronType.no_of_borrow_allowed}</td>
                                <td>${patronType.no_of_day_borrow_allowed}</td>
                                <td>${patronType.no_of_reserve_allowed}</td>
                                <td>${patronType.no_of_day_reserve_allowed}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editPatronType(${patronType.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deletePatronType(${patronType.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#patronTypesTableBody').html(patronTypesTable)
                }else {
                    $('#patronTypesTableBody').html('<tr><td colspan="7"><h5>No Patron Type Record</h5></td></tr>')
                }
            });
        }

        function editPatronType(id) {
            $.get(`/patron-types/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Patron Type')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#name').val(response.data.name)
                $('#fines').val(response.data.fines)
                $('#no_of_borrow_allowed').val(response.data.no_of_borrow_allowed)
                $('#no_of_day_borrow_allowed').val(response.data.no_of_day_borrow_allowed)
                $('#no_of_reserve_allowed').val(response.data.no_of_reserve_allowed)
                $('#no_of_day_reserve_allowed').val(response.data.no_of_day_reserve_allowed)
            });
        }

        function deletePatronType(id) {
            if(confirm('Are you sure you want to delete this patron type?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/patron-types/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getPatronTypes()
                });
            }
        }
    </script>
@endpush
