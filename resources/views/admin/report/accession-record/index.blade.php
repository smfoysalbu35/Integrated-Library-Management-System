@extends('admin.layouts.default-layout')

@section('title', 'Accession Record Report')

@section('content-header')
    <h1>Accession Record</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-bar-chart"></i> Reports</p></li>
        <li class="active"><a href="{{ route('report.accession-records.index') }}">Accession Record Report</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Accession Record Report</h3>
                    <a href="/report/accession-records/print" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Print All</a>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="accessionsTable" class="table table-bordered table-striped table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Accession No.</th>
                                            <th class="text-center">Book Title</th>
                                            <th class="text-center">Call Number</th>
                                            <th class="text-center">ISBN</th>

                                            <th class="text-center">Acquired Date</th>
                                            <th class="text-center">Donnor Name</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Book Shelf/Location Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($accessions as $accession)
                                            <tr>
                                                <td>{{ $accession->accession_no }}</td>
                                                <td>{{ $accession->book->title }}</td>
                                                <td>{{ $accession->book->call_number }}</td>
                                                <td>{{ $accession->book->isbn }}</td>

                                                <td>{{ $accession->acquired_date }}</td>
                                                <td>{{ $accession->donnor_name }}</td>
                                                <td>{{ $accession->price }}</td>
                                                <td>{{ $accession->location->name }}</td>
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
        $('#accessionsTable').DataTable();
    </script>
@endpush
