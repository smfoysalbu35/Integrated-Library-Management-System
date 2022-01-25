@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Book')

@section('content-header')
    <h1>Manage Book</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-book"></i> Cataloging</p></li>
        <li class="active"><a href="{{ route('books.index') }}">Book</a></li>
    </ol>
@endsection

@section('modals')
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Book</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Book Title*</label>
                                    <input type="text" name="title" id="title" class="form-control modal-form">
                                    <span id="titleError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="call_number">Call Number*</label>
                                    <input type="text" name="call_number" id="call_number" class="form-control modal-form">
                                    <span id="callNumberError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="isbn">ISBN*</label>
                                    <input type="text" name="isbn" id="isbn" class="form-control modal-form">
                                    <span id="isbnError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edition">Book Edition*</label>
                                    <input type="text" name="edition" id="edition" class="form-control modal-form">
                                    <span id="editionError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="volume">Book Volume*</label>
                                    <input type="number" name="volume" id="volume" class="form-control modal-form">
                                    <span id="volumeError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="publisher">Publisher*</label>
                                    <input type="text" name="publisher" id="publisher" class="form-control modal-form">
                                    <span id="publisherError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="place_publication">Place Publication*</label>
                                    <input type="text" name="place_publication" id="place_publication" class="form-control modal-form">
                                    <span id="placePublicationError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="copy_right">Copy Right*</label>
                                    <input type="text" name="copy_right" id="copy_right" class="form-control modal-form">
                                    <span id="copyRightError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="copy">Book Copy*</label>
                                    <input type="number" name="copy" id="copy" class="form-control modal-form">
                                    <span id="copyError" class="text-danger"></span>
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
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Book</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Book...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="booksTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Call Number</th>
                                    <th class="text-center">ISBN</th>
                                    <th class="text-center">Book Edition</th>
                                    <th class="text-center">Book Volume</th>
                                    <th class="text-center">Book Copy</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="booksTableBody">

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

                $('.modal-title').html('Add Book')
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
                    url = '/books'
                    confirmationMessage = 'Are you sure you want to add this book?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/books/${id}`
                    confirmationMessage = 'Are you sure you want to update this book?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getBooks()
                            _method === 'POST' ? $('.modal-form').val('') : $('.modal').modal('hide')
                        },
                        error : (error) => {
                            $('#titleError').html(error.responseJSON.errors.title)
                            $('#callNumberError').html(error.responseJSON.errors.call_number)
                            $('#isbnError').html(error.responseJSON.errors.isbn)

                            $('#editionError').html(error.responseJSON.errors.edition)
                            $('#volumeError').html(error.responseJSON.errors.volume)

                            $('#publisherError').html(error.responseJSON.errors.publisher)
                            $('#placePublicationError').html(error.responseJSON.errors.place_publication)

                            $('#copyRightError').html(error.responseJSON.errors.copy_right)
                            $('#copyError').html(error.responseJSON.errors.copy)
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/books/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let booksTable = ''
                        response.data.forEach(book => {
                            booksTable += `
                                <tr>
                                    <td>${book.title}</td>
                                    <td>${book.call_number}</td>
                                    <td>${book.isbn}</td>
                                    <td>${book.edition}</td>
                                    <td>${book.volume}</td>
                                    <td>${book.copy}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editBook(${book.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteBook(${book.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#booksTableBody').html(booksTable)
                    }else {
                        $('#booksTableBody').html('<tr><td colspan="7"><h5>No Book Result</h5></td></tr>')
                    }
                });
            });
        });

        getBooks()
        function getBooks() {
            $.get(`/books/get`, (response) => {
                if(response.data.length > 0) {
                    let booksTable = ''
                    response.data.forEach(book => {
                        booksTable += `
                            <tr>
                                <td>${book.title}</td>
                                <td>${book.call_number}</td>
                                <td>${book.isbn}</td>
                                <td>${book.edition}</td>
                                <td>${book.volume}</td>
                                <td>${book.copy}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editBook(${book.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteBook(${book.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#booksTableBody').html(booksTable)
                }else {
                    $('#booksTableBody').html('<tr><td colspan="7"><h5>No Book Record</h5></td></tr>')
                }
            });
        }

        function editBook(id) {
            $.get(`/books/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Book')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#title').val(response.data.title)
                $('#call_number').val(response.data.call_number)
                $('#isbn').val(response.data.isbn)

                $('#edition').val(response.data.edition)
                $('#volume').val(response.data.volume)

                $('#publisher').val(response.data.publisher)
                $('#place_publication').val(response.data.place_publication)

                $('#copy_right').val(response.data.copy_right)
                $('#copy').val(response.data.copy)
            });
        }

        function deleteBook(id) {
            if(confirm('Are you sure you want to delete this book?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/books/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getBooks()
                });
            }
        }
    </script>
@endpush
