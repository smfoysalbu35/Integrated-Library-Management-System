@extends('patron-web.layouts.default-layout')

@section('title', 'Borrow')

@section('content-header')
    <h1>Borrow Record</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-home"></i> Home</p></li>
        <li class="active"><a href="{{ route('patron-web.borrows.index') }}">Borrow</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Borrow List</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="borrowsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Borrow Date</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrows as $borrow)
                                    <tr>
                                        <td>{{ $borrow->accession->accession_no }}</td>
                                        <td>{{ $borrow->accession->book->title }}</td>
                                        <td>{{ $borrow->borrow_date }}</td>
                                        <td>
                                            @if($borrow->status === 1)
                                                <span class="label label-warning">Borrowed</span>
                                            @else
                                                <span class="label label-success">Returned</span>
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
        $('#borrowsTable').DataTable();
    </script>
@endpush
