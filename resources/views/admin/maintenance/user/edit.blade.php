@extends('admin.layouts.default-layout')

@section('title', 'Edit User')

@section('content-header')
    <a href="{{ route('users.index') }}" class="btn btn-primary"><i class="fa fa-reply-all"></i> Back</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('users.index') }}">User</a></li>
        <li class="active"><a href="{{ route('users.edit', ['user' => $user->id]) }}">Edit User</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit User</h3>
                </div>

                <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="box-body">
                        @include('admin.component.errors-and-messages')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ $user->middle_name }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_type">User Type*</label>
                                    <select name="user_type" id="user_type" class="form-control select2" style="width: 100%;">
                                        @if($user->user_type === 1)
                                            <option value="1" selected>Admin</option>
                                            <option value="2">Library Assistant</option>
                                        @else
                                            <option value="1">Admin</option>
                                            <option value="2" selected>Library Assistant</option>
                                        @endif
                                    </select>
                                    <span class="text-danger">{{ $errors->first('user_type') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status*</label>
                                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                        @if($user->status === 0)
                                            <option value="0" selected>Inactive</option>
                                            <option value="1">Active</option>
                                        @else
                                            <option value="0">Inactive</option>
                                            <option value="1" selected>Active</option>
                                        @endif
                                    </select>
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- col-md-8 -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="image">Image (Optional)</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="divider">

                        <div class="row">
                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="old_password">Old Password (Optional)</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control">
                                    <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="new_password">New Password (Optional)</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control">
                                    <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirm New Password (Optional)</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                                    <span class="text-danger">{{ $errors->first('new_password_confirmation') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="btn-group pull-right">
                            <button type="reset" name="btnReset" id="btnReset" class="btn btn-default">Reset</button>
                            <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
            });

            $('#btnReset').click(function() {
                $('.text-danger').html('');
            });
        });
    </script>
@endpush
