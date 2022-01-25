@extends('admin.layouts.default-layout')

@section('title', 'Author List Report')

@section('content-header')
    <h1>Author List</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.author-lists.index') }}">Author List Report</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Author List Report</h3>
                    <a href="/report/author-lists/print" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Print All</a>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="authorsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Author Name</th>
                                            <th class="text-center">Book(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($authors as $author)
                                            <tr>
                                                <td class="text-center">{{ $author->name }}</td>
                                                <td>
                                                    <ul>
                                                        @forelse($author->book_authors as $bookAuthor)
                                                            <li>{{ $bookAuthor->book->title }}</li>
                                                        @empty
                                                            <b>No Book Record</b>
                                                        @endforelse
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#authorsTable').DataTable();
    </script>
@endpush
