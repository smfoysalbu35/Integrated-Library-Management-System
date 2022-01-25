@extends('patron-web.layouts.default-layout')

@section('title', 'Return Book')

@section('content-header')
    <h1>Return Book Record</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-home"></i> Home</p></li>
        <li class="active"><a href="{{ route('patron-web.return-books.index') }}">Return Book</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Return Book List</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="returnBooksTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Borrow Date</th>
                                    <th class="text-center">Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnBooks as $returnBook)
                                <tr>
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
