@extends('admin.layouts.default-layout')

@section('title', 'Patron Account')

@section('content-header')
    <h1>Manage Patron Account</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-user"></i> Manage Patron</p></li>
        <li class="active"><a href="{{ route('patron-accounts.index') }}">Patron Account</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Patron Account List</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="table-responsive">
                        <table id="patronAccountsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Patron No.</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patronAccounts as $patronAccount)
                                    <tr>
                                        <td>{{ $patronAccount->email }}</td>
                                        <td>{{ $patronAccount->patron->patron_no }}</td>
                                        <td>{{ $patronAccount->patron->first_name . ' ' . $patronAccount->patron->last_name }}</td>
                                        <td style="width: 15%;">
                                            <img src="{{ asset($patronAccount->patron->image) }}" alt="{{ $patronAccount->patron->first_name . ' ' . $patronAccount->patron->last_name }}" style="width: 15%;">
                                        </td>
                                        <td>
                                            @if($patronAccount->status === 1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td colspan="1">
                                            <form action="{{ route('patron-accounts.destroy', ['patron_account' => $patronAccount->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this patron account?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
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
        $('#patronAccountsTable').DataTable();
    </script>
@endpush

