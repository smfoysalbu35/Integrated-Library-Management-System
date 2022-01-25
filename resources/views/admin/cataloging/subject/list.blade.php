@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Subject')

@section('content-header')
    <h1>Manage Subject</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-book"></i> Cataloging</p></li>
        <li class="active"><a href="{{ route('subjects.index') }}">Subject</a></li>
    </ol>
@endsection

@section('modals')
    <div class="modal fade" id="subjectModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="subjectModalTitle">Subject</h4>
                </div>

                <form name="subjectModalForm" id="subjectModalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="subjectId">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Subject Name*</label>
                            <input type="text" name="name" id="subjectName" class="form-control modal-form">
                            <span id="subjectNameError" class="text-danger"></span>
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

    <div class="modal fade" id="bookSubjectModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="bookSubjectModalTitle">Book Subject</h4>
                </div>

                <form name="bookSubjectModalForm" id="bookSubjectModalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="bookSubjectId">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="book_id">Book Title*</label>
                            <select name="book_id" id="bookIdSelect" class="form-control modal-form select2" style="width: 100%;">
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                            <span id="bookIdSelectError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="subject_id">Subject Name*</label>
                            <select name="subject_id" id="subjectIdSelect" class="form-control modal-form select2" style="width: 100%;">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <span id="subjectIdSelectError" class="text-danger"></span>
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
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" name="btnAddSubjectModal" id="btnAddSubjectModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Subject</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSubjectSearch" id="txtSubjectSearch" class="form-control" placeholder="Search Subject...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="subjectsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Subject Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="subjectsTableBody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" name="btnAddBookSubjectModal" id="btnAddBookSubjectModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Book Subject</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtBookSubjectSearch" id="txtBookSubjectSearch" class="form-control" placeholder="Search Book Subject...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="bookSubjectsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Subject Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="bookSubjectsTableBody">

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
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
            });

            $('#btnAddSubjectModal').click(function() {
                $('#subjectModal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('#subjectModalTitle').html('Add Subject')
                $('.modal-submit-button').html('Save')
                $('input[name="_method"]').val('POST')
            });

            $('#subjectModalForm').on('submit', function(e) {
                e.preventDefault()

                $('.text-danger').html('')
                let formData = new FormData(this)
                let _method = $('input[name="_method"]').val()
                let url = ''
                let confirmationMessage = ''

                if(_method === 'POST') {
                    url = '/subjects'
                    confirmationMessage = 'Are you sure you want to add this subject?'
                }

                if(_method === 'PUT') {
                    let id = $('#subjectId').val()
                    url = `/subjects/${id}`
                    confirmationMessage = 'Are you sure you want to update this subject?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getSubjects()
                            _method === 'POST' ? $('.modal-form').val('') : $('#subjectModal').modal('hide')
                        },
                        error : (error) => {
                            $('#subjectNameError').html(error.responseJSON.errors.name)
                        }
                    });
                }
            });

            $('#txtSubjectSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/subjects/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let subjectsTable = ''
                        response.data.forEach(subject => {
                            subjectsTable += `
                                <tr>
                                    <td>${subject.name}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editSubject(${subject.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteSubject(${subject.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#subjectsTableBody').html(subjectsTable)
                    }else {
                        $('#subjectsTableBody').html('<tr><td colspan="2"><h5>No Subject Result</h5></td></tr>')
                    }
                });
            });
        });

        getSubjects()
        function getSubjects() {
            $.get(`/subjects/get`, (response) => {
                if(response.data.length > 0) {
                    let subjectsTable = ''
                    let subjectsSelect = ''

                    response.data.forEach(subject => {
                        subjectsTable += `
                            <tr>
                                <td>${subject.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editSubject(${subject.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteSubject(${subject.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `

                        subjectsSelect += `
                            <option value="${subject.id}">${subject.name}</option>
                        `
                    })

                    $('#subjectsTableBody').html(subjectsTable)
                    $('#subjectIdSelect').html(subjectsSelect)
                }else {
                    $('#subjectsTableBody').html('<tr><td colspan="2"><h5>No Subject Record</h5></td></tr>')
                }
            });
        }

        function editSubject(id) {
            $.get(`/subjects/${id}`, (response) => {
                $('#subjectModal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('#subjectModalTitle').html('Edit Subject')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#subjectId').val(response.data.id)
                $('#subjectName').val(response.data.name)
            });
        }

        function deleteSubject(id) {
            if(confirm('Are you sure you want to delete this subject?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/subjects/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getSubjects()
                });
            }
        }

        /*** Book Subject ***/
        $('#btnAddBookSubjectModal').click(function() {
            $('#bookSubjectModal').modal('show')
            $('.modal-form').val('')
            $('.select2').trigger('change')
            $('.text-danger').html('')

            $('#bookSubjectModalTitle').html('Add Book Subject')
            $('.modal-submit-button').html('Save')
            $('input[name="_method"]').val('POST')
        });

        $('#bookSubjectModalForm').on('submit', function(e) {
            e.preventDefault()

            $('.text-danger').html('')
            let formData = new FormData(this)
            let _method = $('input[name="_method"]').val()
            let url = ''
            let confirmationMessage = ''

            if(_method === 'POST') {
                url = '/book-subjects'
                confirmationMessage = 'Are you sure you want to add this book subject?'
            }

            if(_method === 'PUT') {
                let id = $('#bookSubjectId').val()
                url = `/book-subjects/${id}`
                confirmationMessage = 'Are you sure you want to update this book subject?'
            }

            if(confirm(confirmationMessage)) {
                $.ajax({
                    type : 'POST',
                    url : url,
                    data : formData,
                    contentType : false, processData : false, cache : false,
                    success : (response) => {
                        successNotification(response.message)
                        getBookSubjects()

                        if(_method === 'POST') {
                            $('.modal-form').val('')
                            $('.select2').trigger('change')
                        }

                        if(_method === 'PUT') {
                            $('#bookSubjectModal').modal('hide')
                        }
                    },
                    error : (error) => {
                        $('#bookIdSelectError').html(error.responseJSON.errors.book_id)
                        $('#subjectIdSelectError').html(error.responseJSON.errors.subject_id)
                    }
                });
            }
        });

        $('#txtBookSubjectSearch').keyup(function() {
            let search = $(this).val()
            $.get(`/book-subjects/search`, {search : search}, (response) => {
                if(response.data.length > 0) {
                    let bookSubjectsTable = ''
                    response.data.forEach(bookSubject => {
                        bookSubjectsTable += `
                            <tr>
                                <td>${bookSubject.book.title}</td>
                                <td>${bookSubject.subject.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editBookSubject(${bookSubject.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteBookSubject(${bookSubject.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#bookSubjectsTableBody').html(bookSubjectsTable)
                }else {
                    $('#bookSubjectsTableBody').html('<tr><td colspan="3"><h5>No Book Subject Result</h5></td></tr>')
                }
            });
        });

        getBookSubjects()
        function getBookSubjects() {
            $.get(`/book-subjects`, (response) => {
                if(response.data.length > 0) {
                    let bookSubjectsTable = ''
                    response.data.forEach(bookSubject => {
                        bookSubjectsTable += `
                            <tr>
                                <td>${bookSubject.book.title}</td>
                                <td>${bookSubject.subject.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editBookSubject(${bookSubject.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteBookSubject(${bookSubject.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#bookSubjectsTableBody').html(bookSubjectsTable)
                }else {
                    $('#bookSubjectsTableBody').html('<tr><td colspan="3"><h5>No Book Subject Record</h5></td></tr>')
                }
            });
        }

        function editBookSubject(id) {
            $.get(`/book-subjects/${id}`, (response) => {
                $('#bookSubjectModal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('#bookSubjectModalTitle').html('Edit Book Subject')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#bookSubjectId').val(response.data.id)
                $('#bookIdSelect').val(response.data.book.id)
                $('#bookIdSelect').trigger('change')
                $('#subjectIdSelect').val(response.data.subject.id)
                $('#subjectIdSelect').trigger('change')
            });
        }

        function deleteBookSubject(id) {
            if(confirm('Are you sure you want to delete this book subject?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/book-subjects/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getBookSubjects()
                });
            }
        }
    </script>
@endpush
