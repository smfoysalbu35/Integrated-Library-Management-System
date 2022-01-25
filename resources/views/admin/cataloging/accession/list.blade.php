@extends('admin.layouts.default-layout-with-modal')

@section('title', 'Accession')

@section('content-header')
    <h1>Manage Accession</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-book"></i> Cataloging</p></li>
        <li class="active"><a href="{{ route('accessions.index') }}">Accession</a></li>
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
                    <h4 class="modal-title">Accession</h4>
                </div>

                <form name="modalForm" id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="_method">
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="accession_no">Accession No.*</label>
                            <input type="text" name="accession_no" id="accession_no" class="form-control modal-form">
                            <span id="accessionNoError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="book_id">Book Title*</label>
                            <select name="book_id" id="book_id" class="form-control modal-form select2" style="width: 100%;">
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                            <span id="bookIdError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="location_id">Book Shelf/Location Name*</label>
                            <select name="location_id" id="location_id" class="form-control modal-form select2" style="width: 100%;">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            <span id="locationIdError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="acquired_date">Acquired Date*</label>
                            <input type="date" name="acquired_date" id="acquired_date" class="form-control modal-form">
                            <span id="acquiredDateError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="donnor_name">Name*</label>
                            <input type="text" name="donnor_name" id="donnor_name" class="form-control modal-form">
                            <span id="donnorNameError" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="price">Price*</label>
                            <input type="number" name="price" id="price" class="form-control modal-form">
                            <span id="priceNameError" class="text-danger"></span>
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
                            <button type="button" name="btnAddModal" id="btnAddModal" class="btn btn-primary"><i class="fa fa-plus"></i> Add Accession</button>
                        </div>

                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text" name="txtSearch" id="txtSearch" class="form-control" placeholder="Search Accession...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="accessionsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Call Number</th>
                                    <th class="text-center">ISBN</th>
                                    <th class="text-center">Book Edition</th>
                                    <th class="text-center">Book Volume</th>
                                    <th class="text-center">Book Shelf/Location Name</th>
                                    <th class="text-center">Action</th>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
            });

            $('#btnAddModal').click(function() {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.select2').trigger('change')
                $('.text-danger').html('')

                $('.modal-title').html('Add Accession')
                $('.modal-submit-button').html('Save')
                $('input[name="_method"]').val('POST')

                getAccessionNo()
            });

            $('#modalForm').on('submit', function(e) {
                e.preventDefault()

                $('.text-danger').html('')
                let formData = new FormData(this)
                let _method = $('input[name="_method"]').val()
                let url = ''
                let confirmationMessage = ''

                if(_method === 'POST') {
                    url = '/accessions'
                    confirmationMessage = 'Are you sure you want to add this accession?'
                }

                if(_method === 'PUT') {
                    let id = $('#id').val()
                    url = `/accessions/${id}`
                    confirmationMessage = 'Are you sure you want to update this accession?'
                }

                if(confirm(confirmationMessage)) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : formData,
                        contentType : false, processData : false, cache : false,
                        success : (response) => {
                            successNotification(response.message)
                            getAccessions()

                            if(_method === 'POST') {
                                $('.modal-form').val('')
                                $('.select2').trigger('change')
                                getAccessionNo()
                            }

                            if(_method === 'PUT') {
                                $('.modal').modal('hide')
                            }

                        },
                        error : (error) => {
                            if(error.status === 400) {
                                warningNotification(error.responseJSON.error)
                                $('#bookIdError').html(error.responseJSON.error)
                            }

                            if(error.status === 422) {
                                $('#accessionNoError').html(error.responseJSON.errors.accession_no)

                                $('#bookIdError').html(error.responseJSON.errors.book_id)
                                $('#locationIdError').html(error.responseJSON.errors.location_id)

                                $('#acquiredDateError').html(error.responseJSON.errors.acquired_date)
                                $('#donnorNameError').html(error.responseJSON.errors.donnor_name)
                                $('#priceNameError').html(error.responseJSON.errors.price)
                            }
                        }
                    });
                }
            });

            $('#txtSearch').keyup(function() {
                let search = $(this).val()
                $.get(`/accessions/search`, {search : search}, (response) => {
                    if(response.data.length > 0) {
                        let accessionsTable = ''
                        response.data.forEach(accession => {
                            accessionsTable += `
                                <tr>
                                    <td>${accession.accession_no}</td>
                                    <td>${accession.book.title}</td>
                                    <td>${accession.book.call_number}</td>
                                    <td>${accession.book.isbn}</td>

                                    <td>${accession.book.edition}</td>
                                    <td>${accession.book.volume}</td>

                                    <td>${accession.location.name}</td>
                                    <td colspan="2">
                                        <button type="button" onclick="editAccession(${accession.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                        <button type="button" onclick="deleteAccession(${accession.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            `
                        })
                        $('#accessionsTableBody').html(accessionsTable)
                    }else {
                        $('#accessionsTableBody').html('<tr><td colspan="8"><h5>No Accession Result</h5></td></tr>')
                    }
                });
            });
        });

        getAccessions()
        function getAccessions() {
            $.get(`/accessions/get`, (response) => {
                if(response.data.length > 0) {
                    let accessionsTable = ''
                    response.data.forEach(accession => {
                        accessionsTable += `
                            <tr>
                                <td>${accession.accession_no}</td>
                                <td>${accession.book.title}</td>
                                <td>${accession.book.call_number}</td>
                                <td>${accession.book.isbn}</td>

                                <td>${accession.book.edition}</td>
                                <td>${accession.book.volume}</td>

                                <td>${accession.location.name}</td>
                                <td colspan="2">
                                    <button type="button" onclick="editAccession(${accession.id})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" onclick="deleteAccession(${accession.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        `
                    })
                    $('#accessionsTableBody').html(accessionsTable)
                }else {
                    $('#accessionsTableBody').html('<tr><td colspan="8"><h5>No Accession Record</h5></td></tr>')
                }
            });
        }

        function editAccession(id) {
            $.get(`/accessions/${id}`, (response) => {
                $('.modal').modal('show')
                $('.modal-form').val('')
                $('.text-danger').html('')

                $('.modal-title').html('Edit Accession')
                $('.modal-submit-button').html('Update')
                $('input[name="_method"]').val('PUT')

                $('#id').val(response.data.id)
                $('#accession_no').val(response.data.accession_no)

                $('#book_id').val(response.data.book.id)
                $('#book_id').trigger('change')
                $('#location_id').val(response.data.location.id)
                $('#location_id').trigger('change')

                $('#acquired_date').val(response.data.acquired_date)
                $('#donnor_name').val(response.data.donnor_name)
                $('#price').val(response.data.price)
            });
        }

        function deleteAccession(id) {
            if(confirm('Are you sure you want to delete this accession?')) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.post(`/accessions/${id}`, {'_token' : CSRF_TOKEN, '_method' : 'DELETE'}, (response) => {
                    deleteNotification(response.message)
                    getAccessions()
                });
            }
        }

        function getAccessionNo() {
            let today = new Date()
            let YYYY = today.getFullYear()
            let MM = ('0' + (today.getMonth() + 1)).slice(-2)
            let DD = ('0' + today.getDate()).slice(-2)
            let currentDate = YYYY + '-' + MM + '-' + DD

            $.get(`/accessions/count`, (response) => {
                let count = response.count + 1
                let accessionNo = count >= 1 && count <= 9 ? `${currentDate}-000${count}` :
                        count >= 10 && count <= 99 ? `${currentDate}-00${count}` :
                        count >= 100 && count <= 999 ? `${currentDate}-0${count}` : `${currentDate}-${count}`

                $('#accession_no').val(accessionNo)
            });
        }
    </script>
@endpush
