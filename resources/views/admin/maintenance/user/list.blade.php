@extends('admin.layouts.default-layout')

@section('title', 'User')

@section('content-header')
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('users.index') }}">User</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">User List</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="table-responsive">
                        <table id="usersTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">User Type</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                        <td style="width: 15%;">
                                            <img src="{{ asset($user->image) }}" alt="{{ $user->first_name . ' ' . $user->last_name }}" style="width: 15%;">
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->user_type === 1 ? 'Administrator' : 'Library Assistant' }}</td>
                                        <td>
                                            @if($user->status === 1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td colspan="2">
                                            <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                                {{-- <button type="button" onclick="#" class="btn btn-default btn-sm"><i class="fa fa-send"></i> Send Password Reset Link</button> --}}
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
        $('#usersTable').DataTable();
    </script>
@endpush
