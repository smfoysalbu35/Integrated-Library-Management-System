@extends('admin.layouts.default-layout')

@section('title', 'Library Holding Report')

@section('content-header')
    <h1>Library Holding</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.library-holdings.index') }}">Library Holding Report</a></li>
    </ol>
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <!-- col-md-12 -->
        <div class="col-md-12">
            <!-- box box-primary -->
            <div class="box box-primary">
                <!-- box-header -->
                <div class="box-header with-border">
                    <h3 class="box-title">Library Holding Report</h3>
                    <a href="/report/library-holdings/print" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Print All</a>
                </div>
                <!-- box-header -->

                <!-- box-body -->
                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="booksTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Book Title</th>
                                            <th class="text-center">Call Number</th>
                                            <th class="text-center">ISBN</th>

                                            <th class="text-center">Book Edition</th>
                                            <th class="text-center">Book Volume</th>

                                            <th class="text-center">Publisher</th>
                                            <th class="text-center">Place Publication</th>
                                            <th class="text-center">Copy Right</th>
                                            <th class="text-center">Book Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($books as $book)
                                            <tr>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->call_number }}</td>
                                                <td>{{ $book->isbn }}</td>

                                                <td>{{ $book->edition }}</td>
                                                <td>{{ $book->volume }}</td>

                                                <td>{{ $book->publisher }}</td>
                                                <td>{{ $book->place_publication }}</td>
                                                <td>{{ $book->copy_right }}</td>
                                                <td>{{ $book->copy }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- box-body -->
            </div>
            <!-- box box-primary -->
        </div>
        <!-- col-md-12 -->
    </div>
    <!-- row -->
@endsection

@push('scripts')
    <script>
        $('#booksTable').DataTable();
    </script>
@endpush
