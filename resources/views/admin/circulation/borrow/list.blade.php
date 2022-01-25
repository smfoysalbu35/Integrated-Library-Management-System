@extends('admin.layouts.default-layout')

@section('title', 'Borrow')

@section('content-header')
    <a href="{{ route('borrows.create') }}" class="btn btn-primary"><i class="fa fa-book"></i> Borrow Book</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-refresh"></i> Circulation</p></li>
        <li class="active"><a href="{{ route('borrows.index') }}">Borrow</a></li>
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
                    @include('admin.component.errors-and-messages')

                    <div class="table-responsive">
                        <table id="borrowsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>
                                    <th class="text-center">Borrow Date</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrows as $borrow)
                                    <tr>
                                        <td>{{ $borrow->patron->patron_no }}</td>
                                        <td>{{ $borrow->patron->first_name . ' ' . $borrow->patron->last_name }}</td>
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
