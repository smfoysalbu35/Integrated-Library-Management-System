@extends('admin.layouts.default-layout')

@section('title', 'Subject List Report')

@section('content-header')
    <h1>Subject List</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.subject-lists.index') }}">Subject List Report</a></li>
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
                    <h3 class="box-title">Subject List Report</h3>
                    <a href="/report/subject-lists/print" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Print All</a>
                </div>
                <!-- box-header -->

                <!-- box-body -->
                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="subjectsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Subject Name</th>
                                            <th class="text-center">Book(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjects as $subject)
                                            <tr>
                                                <td class="text-center">{{ $subject->name }}</td>
                                                <td>
                                                    <ul>
                                                        @forelse($subject->book_subjects as $bookSubject)
                                                            <li>{{ $bookSubject->book->title }}</li>
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
        $('#subjectsTable').DataTable();
    </script>
@endpush
