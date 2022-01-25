@extends('admin.layouts.default-layout')

@section('title', 'Return')

@section('content-header')
    <a href="{{ route('return-books.create') }}" class="btn btn-primary"><i class="fa fa-book"></i> Return Book</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
        <li class="active"><a href="{{ route('return-books.index') }}">Return</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Return List</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="table-responsive">
                        <table id="returnBooksTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Borrow Date</th>
                                    <th class="text-center">Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnBooks as $returnBook)
                                    <tr>
                                        <td>{{ $returnBook->patron->patron_no }}</td>
                                        <td>{{ $returnBook->patron->first_name . ' ' . $returnBook->patron->last_name }}</td>
                                        <td>{{ $returnBook->accession->accession_no }}</td>
                                        <td>{{ $returnBook->accession->book->title }}</td>
                                        <td>{{ $returnBook->borrow->borrow_date }}</td>
                                        <td>{{ $returnBook->return_date }}</td>
                                    </tr>
                                @endforeach
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
        $('#returnBooksTable').DataTable();
    </script>
@endpush
