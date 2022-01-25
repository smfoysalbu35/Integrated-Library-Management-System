@extends('admin.layouts.default-layout')

@section('title', 'Add User')

@section('content-header')
    <a href="{{ route('users.index') }}" class="btn btn-primary"><i class="fa fa-reply-all"></i> Back</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-gear"></i> System Maintenance</p></li>
        <li class="active"><a href="{{ route('users.index') }}">User</a></li>
        <li class="active"><a href="{{ route('users.create') }}">Add User</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add User</h3>
                </div>

                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        @include('admin.component.errors-and-messages')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_type">User Type*</label>
                                    <select name="user_type" id="user_type" class="form-control select2" style="width: 100%;">
                                        @if(old('user_type'))
                                            @if(old('user_type') == 1)
                                                <option value="1" selected>Admin</option>
                                                <option value="2">Library Assistant</option>
                                            @else
                                                <option value="1">Admin</option>
                                                <option value="2" selected>Library Assistant</option>
                                            @endif
                                        @else
                                            <option value="1">Admin</option>
                                            <option value="2">Library Assistant</option>
                                        @endif
                                    </select>
                                    <span class="text-danger">{{ $errors->first('user_type') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status*</label>
                                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                        @if(old('status'))
                                            @if(old('status') == 0)
                                                <option value="0" selected>Inactive</option>
                                                <option value="1">Active</option>
                                            @else
                                                <option value="0">Inactive</option>
                                                <option value="1" selected>Active</option>
                                            @endif
                                        @else
                                            <option value="0">Inactive</option>
                                            <option value="1">Active</option>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Password (Optional)</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password (Optional)</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
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
