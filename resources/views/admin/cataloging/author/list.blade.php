@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Author')

@section('content-header')
    <h1>Manage Author</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-book"></i> Cataloging</p></li>
        <li class="active"><a href="{{ route('authors.index') }}">Author</a></li>
    </ol>
@endsection

@section('modals')
    <div class="modal fade" id="authorModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="authorModalTitle">Author</h4>
                </div>

                <form name="authorModalForm" id="authorModalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="authorId">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Author Name*</label>
                            <input type="text" name="name" id="authorName" class="form-control modal-form">
                            <span id="authorNameError" class="text-danger"></span>
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

    <div class="modal fade" id="bookAuthorModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="bookAuthorModalTitle">Book Author</h4>
                </div>

                <form name="bookAuthorModalForm" id="bookAuthorModalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="bookAuthorId">

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
                            <label for="author_id">Author Name*</label>
                            <select name="author_id" id="authorIdSelect" class="form-control modal-form select2" style="width: 100%;">
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                            <span id="authorIdSelectError" class="text-danger"></span>
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
                            <button type="button" name="btnAddAuthorModal" id="btnAddAuthorModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Author</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtAuthorSearch" id="txtAuthorSearch" class="form-control" placeholder="Search Author...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="authorsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Author Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="authorsTableBody">

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
                            <button type="button" name="btnAddBookAuthorModal" id="btnAddBookAuthorModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Book Author</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtBookAuthorSearch" id="txtBookAuthorSearch" class="form-control" placeholder="Search Book Author...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="bookAuthorsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Author Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="bookAuthorsTableBody">

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

            $('#btnAddAuthorModal').click(function() {
                $('#authorModal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('#authorModalTitle').html('Add Author')
                $('.modal-submit-button').html('Save')
                $('input[name="_method"]').val('POST')
            });

            $('#authorModalForm').on('submit', function(e) {
                e.preventDefault()

                $('.text-danger').html('')
                let formData = new FormData(this)
                let _method = $('input[name="_method"]').val()
                let url = ''
                let confirmationMessage = ''

                if(_method === 'POST') {
                    url = '/authors'
                    confirmationMessage = 'Are you sure you want to add this author?'
                }

                if(_method === 'PUT') {
                    let id = $('#authorId').val()
                    url = `/authors/${id}`
                    confirmationMessage = 'Are you sure you want to update this author?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getAuthors()
                            _method === 'POST' ? $('.modal-form').val('') : $('#authorModal').modal('hide')
                        },
                        error : (error) => {
                            $('#authorNameError').html(error.responseJSON.errors.name)
                        }
                    });
                }
            });

            $('#txtAuthorSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/authors/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let authorsTable = ''
                        response.data.forEach(author => {
                            authorsTable += `
                                <tr>
                                    <td>${author.name}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editAuthor(${author.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteAuthor(${author.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#authorsTableBody').html(authorsTable)
                    }else {
                        $('#authorsTableBody').html('<tr><td colspan="2"><h5>No Author Result</h5></td></tr>')
                    }
                });
            });
        });

        getAuthors()
        function getAuthors() {
            $.get(`/authors/get`, (response) => {
                if(response.data.length > 0) {
                    let authorsTable = ''
                    let authorsSelect = ''

                    response.data.forEach(author => {
                        authorsTable += `
                            <tr>
                                <td>${author.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editAuthor(${author.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteAuthor(${author.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `

                        authorsSelect += `
                            <option value="${author.id}">${author.name}</option>
                        `
                    })

                    $('#authorsTableBody').html(authorsTable)
                    $('#authorIdSelect').html(authorsSelect)
                }else {
                    $('#authorsTableBody').html('<tr><td colspan="2"><h5>No Author Record</h5></td></tr>')
                }
            });
        }

        function editAuthor(id) {
            $.get(`/authors/${id}`, (response) => {
                $('#authorModal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('#authorModalTitle').html('Edit Author')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#authorId').val(response.data.id)
                $('#authorName').val(response.data.name)
            });
        }

        function deleteAuthor(id) {
            if(confirm('Are you sure you want to delete this author?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/authors/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getAuthors()
                });
            }
        }

        /*** Book Author ***/
        $('#btnAddBookAuthorModal').click(function() {
            $('#bookAuthorModal').modal('show')
            $('.modal-form').val('')
            $('.select2').trigger('change')
            $('.text-danger').html('')

            $('#bookAuthorModalTitle').html('Add Book Author')
            $('.modal-submit-button').html('Save')
            $('input[name="_method"]').val('POST')
        });

        $('#bookAuthorModalForm').on('submit', function(e) {
            e.preventDefault()

            $('.text-danger').html('')
            let formData = new FormData(this)
            let _method = $('input[name="_method"]').val()
            let url = ''
            let confirmationMessage = ''

            if(_method === 'POST') {
                url = '/book-authors'
                confirmationMessage = 'Are you sure you want to add this book author?'
            }

            if(_method === 'PUT') {
                let id = $('#bookAuthorId').val()
                url = `/book-authors/${id}`
                confirmationMessage = 'Are you sure you want to update this book author?'
            }

            if(confirm(confirmationMessage)) {
                $.ajax({
                    type : 'POST',
                    url : url,
                    data : formData,
                    contentType : false, processData : false, cache : false,
                    success : (response) => {
                        successNotification(response.message)
                        getBookAuthors()

                        if(_method === 'POST') {
                            $('.modal-form').val('')
                            $('.select2').trigger('change')
                        }

                        if(_method === 'PUT') {
                            $('#bookAuthorModal').modal('hide')
                        }
                    },
                    error : (error) => {
                        $('#bookIdSelectError').html(error.responseJSON.errors.book_id)
                        $('#authorIdSelectError').html(error.responseJSON.errors.author_id)
                    }
                });
            }
        });

        $('#txtBookAuthorSearch').keyup(function() {
            let search = $(this).val()
            $.get(`/book-authors/search`, {search : search}, (response) => {
                if(response.data.length > 0) {
                    let bookAuthorsTable = ''
                    response.data.forEach(bookAuthor => {
                        bookAuthorsTable += `
                            <tr>
                                <td>${bookAuthor.book.title}</td>
                                <td>${bookAuthor.author.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editBookAuthor(${bookAuthor.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteBookAuthor(${bookAuthor.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#bookAuthorsTableBody').html(bookAuthorsTable)
                }else {
                    $('#bookAuthorsTableBody').html('<tr><td colspan="3"><h5>No Book Author Result</h5></td></tr>')
                }
            });
        });

        getBookAuthors()
        function getBookAuthors() {
            $.get(`/book-authors`, (response) => {
                if(response.data.length > 0) {
                    let bookAuthorsTable = ''
                    response.data.forEach(bookAuthor => {
                        bookAuthorsTable += `
                            <tr>
                                <td>${bookAuthor.book.title}</td>
                                <td>${bookAuthor.author.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editBookAuthor(${bookAuthor.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteBookAuthor(${bookAuthor.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#bookAuthorsTableBody').html(bookAuthorsTable)
                }else {
                    $('#bookAuthorsTableBody').html('<tr><td colspan="3"><h5>No Book Author Record</h5></td></tr>')
                }
            });
        }

        function editBookAuthor(id) {
            $.get(`/book-authors/${id}`, (response) => {
                $('#bookAuthorModal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('#bookAuthorModalTitle').html('Edit Book Author')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#bookAuthorId').val(response.data.id)
                $('#bookIdSelect').val(response.data.book.id)
                $('#bookIdSelect').trigger('change')
                $('#authorIdSelect').val(response.data.author.id)
                $('#authorIdSelect').trigger('change')
            });
        }

        function deleteBookAuthor(id) {
            if(confirm('Are you sure you want to delete this book author?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/book-authors/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getBookAuthors()
                });
            }
        }
    </script>
@endpush
