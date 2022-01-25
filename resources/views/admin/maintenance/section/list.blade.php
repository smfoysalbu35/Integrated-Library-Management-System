@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Section')

@section('content-header')
    <h1>Manage Section</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('sections.index') }}">Section</a></li>
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
                    <h4 class="modal-title">Section</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Section*</label>
                            <input type="text" name="name" id="name" class="form-control modal-form">
                            <span id="nameError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="grade_level_id">Grade Level*</label>
                            <select name="grade_level_id" id="grade_level_id" class="form-control modal-form select2" style="width: 100%;">
                                @foreach ($gradeLevels as $gradeLevel)
                                    <option value="{{ $gradeLevel->id }}">{{ $gradeLevel->grade_level }}</option>
                                @endforeach
                            </select>
                            <span id="gradeLevelIdError" class="text-danger"></span>
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
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Section</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Section...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="sectionsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Grade Level</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="sectionsTableBody">

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
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
            });

            $('#btnAddModal').click(function() {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.select2').trigger('change')
                $('.text-danger').html('')

                $('.modal-title').html('Add Section')
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
                    url = '/sections'
                    confirmationMessage = 'Are you sure you want to add this section?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/sections/${id}`
                    confirmationMessage = 'Are you sure you want to update this section?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getSections()

                            if(_method === 'POST') {
                                $('.modal-form').val('')
                                $('.select2').trigger('change')
                            }

                            if(_method === 'PUT') {
                                $('.modal').modal('hide')
                            }
                        },
                        error : (error) => {
                            $('#nameError').html(error.responseJSON.errors.name)
                            $('#gradeLevelIdError').html(error.responseJSON.errors.grade_level_id)
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/sections/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let sectionsTable = '';
                        response.data.forEach(section => {
                            sectionsTable += `
                                <tr>
                                    <td>${section.name}</td>
                                    <td>${section.grade_level.grade_level}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editSection(${section.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteSection(${section.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#sectionsTableBody').html(sectionsTable)
                    }else {
                        $('#sectionsTableBody').html('<tr><td colspan="3"><h5>No Section Result</h5></td></tr>')
                    }
                });
            });
        });

        getSections()
        function getSections() {
            $.get(`/sections/get`, (response) => {
                if(response.data.length > 0) {
                    let sectionsTable = '';
                    response.data.forEach(section => {
                        sectionsTable += `
                            <tr>
                                <td>${section.name}</td>
                                    <td>${section.grade_level.grade_level}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editSection(${section.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteSection(${section.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#sectionsTableBody').html(sectionsTable)
                }else {
                    $('#sectionsTableBody').html('<tr><td colspan="3"><h5>No Section Record</h5></td></tr>')
                }
            });
        }

        function editSection(id) {
            $.get(`/sections/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Section')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#name').val(response.data.name)
                $('#grade_level_id').val(response.data.grade_level.id)
                $('#grade_level_id').trigger('change')
            });
        }

        function deleteSection(id) {
            if(confirm('Are you sure you want to delete this section?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/sections/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getSections()
                });
            }
        }
    </script>
@endpush
