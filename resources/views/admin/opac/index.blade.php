@extends('admin.layouts.default-layout')

@section('title', 'OPAC')

@section('content-header')
    <h1>Online Public Access Catalog</h1>
    <ol class="breadcrumb">
        <li class="active"><a href="{{ route('opac.index') }}"><i class="fa fa-search"></i> OPAC</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Accession List</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="table-responsive">
                        <table id="opacTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Call Number</th>
                                    <th class="text-center">ISBN</th>
                                    <th class="text-center">Book Edition</th>
                                    <th class="text-center">Book Volume</th>
                                    <th class="text-center">Book Shelf/Location Name</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accessions as $accession)
                                    <tr>
                                        <td>{{ $accession->accession_no }}</td>
                                        <td>{{ $accession->book->title }}</td>
                                        <td>{{ $accession->book->call_number }}</td>
                                        <td>{{ $accession->book->isbn }}</td>
                                        <td>{{ $accession->book->edition }}</td>
                                        <td>{{ $accession->book->volume }}</td>
                                        <td>{{ $accession->location->name }}</td>
                                        <td>
                                            @if($accession->status === 1)
                                                <span class="label label-success">Available</span>
                                            @else
                                                <span class="label label-danger">Not Available</span>
                                            @endif
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
@endsection

@push('scripts')
    <script>
        $('#opacTable').DataTable();
    </script>
@endpush

