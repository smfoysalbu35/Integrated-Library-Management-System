@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Location')

@section('content-header')
    <h1>Manage Location</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('locations.index') }}">Location</a></li>
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
                    <h4 class="modal-title">Location</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Book Shelf Name*</label>
                            <input type="text" name="name" id="name" class="form-control modal-form">
                            <span id="nameError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="symbol">Book Shelf Symbol*</label>
                            <input type="text" name="symbol" id="symbol" class="form-control modal-form">
                            <span id="symbolError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="allowed">Allowed Books*</label>
                            <input type="text" name="allowed" id="allowed" class="form-control modal-form">
                            <span id="allowedError" class="text-danger"></span>
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
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Location</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Location...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="locationsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Book Shelf Name</th>
                                    <th class="text-center">Book Shelf Symbol</th>
                                    <th class="text-center">Allowed Books</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="locationsTableBody">

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

                $('.modal-title').html('Add Location')
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
                    url = '/locations'
                    confirmationMessage = 'Are you sure you want to add this location?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/locations/${id}`
                    confirmationMessage = 'Are you sure you want to update this location?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getLocations()
                            _method === 'POST' ? $('.modal-form').val('') : $('.modal').modal('hide')
                        },
                        error : (error) => {
                            $('#nameError').html(error.responseJSON.errors.name)
                            $('#symbolError').html(error.responseJSON.errors.symbol)
                            $('#allowedError').html(error.responseJSON.errors.allowed)
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/locations/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let locationsTable = ''
                        response.data.forEach(location => {
                            locationsTable += `
                                <tr>
                                    <td>${location.name}</td>
                                    <td>${location.symbol}</td>
                                    <td>${location.allowed}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editLocation(${location.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteLocation(${location.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#locationsTableBody').html(locationsTable)
                    }else {
                        $('#locationsTableBody').html('<tr><td colspan="4"><h5>No Location Result</h5></td></tr>')
                    }
                });
            });
        });

        getLocations()
        function getLocations() {
            $.get(`/locations/get`, (response) => {
                if(response.data.length > 0) {
                    let locationsTable = ''
                    response.data.forEach(location => {
                        locationsTable += `
                            <tr>
                                <td>${location.name}</td>
                                <td>${location.symbol}</td>
                                <td>${location.allowed}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editLocation(${location.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteLocation(${location.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#locationsTableBody').html(locationsTable)
                }else {
                    $('#locationsTableBody').html('<tr><td colspan="4"><h5>No Location Record</h5></td></tr>')
                }
            });
        }

        function editLocation(id) {
            $.get(`/locations/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Location')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#name').val(response.data.name)
                $('#symbol').val(response.data.symbol)
                $('#allowed').val(response.data.allowed)
            });
        }

        function deleteLocation(id) {
            if(confirm('Are you sure you want to delete this location?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/locations/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getLocations()
                });
            }
        }
    </script>
@endpush
