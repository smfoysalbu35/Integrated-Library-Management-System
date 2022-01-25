@extends('admin.layouts.default-layout')

@section('title', 'Patron')

@section('content-header')
    <a href="{{ route('patrons.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Patron</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-user"></i> Manage Patron</p></li>
        <li class="active"><a href="{{ route('patrons.index') }}">Patron</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Patron List</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="table-responsive">
                        <table id="patronsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Patron Type</th>
                                    <th class="text-center">Section</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patrons as $patron)
                                    <tr>
                                        <td>{{ $patron->patron_no }}</td>
                                        <td>{{ $patron->first_name . ' ' . $patron->last_name }}</td>
                                        <td style="width: 15%;">
                                            <img src="{{ asset($patron->image) }}" alt="{{ $patron->first_name . ' ' . $patron->last_name }}" style="width: 15%;">
                                        </td>
                                        <td>{{ $patron->patron_type->name }}</td>
                                        <td>{{ $patron->section->name }}</td>
                                        <td colspan="2">
                                            <form action="{{ route('patrons.destroy', ['patron' => $patron->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <a href="{{ route('patrons.edit', ['patron' => $patron->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this patron?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
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
        $('#patronsTable').DataTable();
    </script>
@endpush

