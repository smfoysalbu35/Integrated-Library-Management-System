@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Grade Level')

@section('content-header')
    <h1>Manage Grade Level</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('grade-levels.index') }}">Grade Level</a></li>
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
                    <h4 class="modal-title">Grade Level</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="grade_level">Grade Level*</label>
                            <input type="number" name="grade_level" id="grade_level" class="form-control modal-form">
                            <span id="gradeLevelError" class="text-danger"></span>
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
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Grade Level</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Grade Level...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="gradeLevelsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Grade Level</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="gradeLevelsTableBody">

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

                $('.modal-title').html('Add Grade Level')
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
                    url = '/grade-levels'
                    confirmationMessage = 'Are you sure you want to add this grade level?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/grade-levels/${id}`
                    confirmationMessage = 'Are you sure you want to update this grade level?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getGradeLevels()
                            _method === 'POST' ? $('.modal-form').val('') : $('.modal').modal('hide')
                        },
                        error : (error) => {
                            $('#gradeLevelError').html(error.responseJSON.errors.grade_level)
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/grade-levels/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let gradeLevelTable = ''
                        response.data.forEach(gradeLevel => {
                            gradeLevelTable += `
                                <tr>
                                    <td>${gradeLevel.grade_level}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editGradeLevel(${gradeLevel.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteGradeLevel(${gradeLevel.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#gradeLevelsTableBody').html(gradeLevelTable)
                    }else {
                        $('#gradeLevelsTableBody').html('<tr><td colspan="2"><h5>No Grade Level Result</h5></td></tr>')
                    }
                });
            });
        });

        getGradeLevels()
        function getGradeLevels() {
            $.get(`/grade-levels/get`, (response) => {
                if(response.data.length > 0) {
                    let gradeLevelTable = ''
                    response.data.forEach(gradeLevel => {
                        gradeLevelTable += `
                            <tr>
                                <td>${gradeLevel.grade_level}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editGradeLevel(${gradeLevel.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteGradeLevel(${gradeLevel.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#gradeLevelsTableBody').html(gradeLevelTable)
                }else {
                    $('#gradeLevelsTableBody').html('<tr><td colspan="2"><h5>No Grade Level Record</h5></td></tr>')
                }
            });
        }

        function editGradeLevel(id) {
            $.get(`/grade-levels/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Grade Level')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#grade_level').val(response.data.grade_level)
            });
        }

        function deleteGradeLevel(id) {
            if(confirm('Are you sure you want to delete this grade level?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/grade-levels/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getGradeLevels()
                });
            }
        }
    </script>
@endpush
