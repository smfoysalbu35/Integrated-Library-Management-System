@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Close Date')

@section('content-header')
    <h1>Manage Close Date</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('close-dates.index') }}">Close Date</a></li>
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
                    <h4 class="modal-title">Close Date</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="close_date">Close Date*</label>
                            <input type="date" name="close_date" id="close_date" class="form-control modal-form">
                            <span id="closeDateError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description*</label>
                            <input type="text" name="description" id="description" class="form-control modal-form">
                            <span id="descriptionError" class="text-danger"></span>
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
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Close Date</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Close Date...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="closeDatesTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Close Date</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="closeDatesTableBody">

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

                $('.modal-title').html('Add Close Date')
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
                    url = '/close-dates'
                    confirmationMessage = 'Are you sure you want to add this close date?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/close-dates/${id}`
                    confirmationMessage = 'Are you sure you want to update this close date?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getCloseDates()
                            _method === 'POST' ? $('.modal-form').val('') : $('.modal').modal('hide')
                        },
                        error : (error) => {
                            $('#closeDateError').html(error.responseJSON.errors.close_date)
                            $('#descriptionError').html(error.responseJSON.errors.description)
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/close-dates/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let closeDateTable = '';
                        response.data.forEach(closeDate => {
                            closeDateTable += `
                                <tr>
                                    <td>${closeDate.close_date}</td>
                                    <td>${closeDate.description}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editCloseDate(${closeDate.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteCloseDate(${closeDate.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#closeDatesTableBody').html(closeDateTable)
                    }else {
                        $('#closeDatesTableBody').html('<tr><td colspan="3"><h5>No Close Date Result</h5></td></tr>')
                    }
                });
            });
        });

        getCloseDates()
        function getCloseDates() {
            $.get(`/close-dates/get`, (response) => {
                if(response.data.length > 0) {
                    let closeDateTable = ''
                    response.data.forEach(closeDate => {
                        closeDateTable += `
                            <tr>
                                <td>${closeDate.close_date}</td>
                                <td>${closeDate.description}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editCloseDate(${closeDate.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteCloseDate(${closeDate.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#closeDatesTableBody').html(closeDateTable)
                }else {
                    $('#closeDatesTableBody').html('<tr><td colspan="3"><h5>No Close Date Record</h5></td></tr>')
                }
            });
        }

        function editCloseDate(id) {
            $.get(`/close-dates/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Close Date')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#close_date').val(response.data.close_date)
                $('#description').val(response.data.description)
            });
        }

        function deleteCloseDate(id) {
            if(confirm('Are you sure you want to delete this close date?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/close-dates/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getCloseDates()
                });
            }
        }
    </script>
@endpush
