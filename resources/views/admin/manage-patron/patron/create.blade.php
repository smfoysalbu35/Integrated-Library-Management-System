@extends('admin.layouts.default-layout')

@section('title', 'Add Patron')

@section('content-header')
    <a href="{{ route('patrons.index') }}" class="btn btn-primary"><i class="fa fa-reply-all"></i> Back</a>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-user"></i> Manage Patron</p></li>
        <li class="active"><a href="{{ route('patrons.index') }}">Patron</a></li>
        <li class="active"><a href="{{ route('patrons.create') }}">Add Patron</a></li>
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
                    <h3 class="box-title">Add Patron</h3>
                </div>
                <!-- box-header -->

                <form action="{{ route('patrons.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- box-body -->
                    <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <!-- row -->
                        <div class="row">
                            <!-- col-md-8 -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="patron_no">Patron No.*</label>
                                    <input type="text" name="patron_no" id="patron_no" value="{{ $patronNo }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('patron_no') }}</span>
                                </div>
                            </div>
                            <!-- col-md-8 -->
                        </div>
                        <!-- row -->

                        <hr class="divider">

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->
                        </div>
                        <!-- row -->

                        <hr class="divider">

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="house_no">House No.*</label>
                                    <input type="text" name="house_no" id="house_no" value="{{ old('house_no') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('house_no') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="street">Street*</label>
                                    <input type="text" name="street" id="street" value="{{ old('street') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('street') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="barangay">District*</label>
                                    <input type="text" name="barangay" id="barangay" value="{{ old('barangay') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('barangay') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->
                        </div>
                        <!-- row -->

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="municipality">Thana*</label>
                                    <input type="text" name="municipality" id="municipality" value="{{ old('municipality') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('municipality') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="province">Country*</label>
                                    <input type="text" name="province" id="province" value="{{ old('province') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('province') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->
                        </div>
                        <!-- row -->

                        <hr class="divider">

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_no">Contact No.*</label>
                                    <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no') }}" class="form-control">
                                    <span class="text-danger">{{ $errors->first('contact_no') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="patron_type_id">Patron Type*</label>
                                    <select name="patron_type_id" id="patron_type_id" class="form-control select2" style="width: 100%;">
                                        @foreach($patronTypes as $patronType)
                                            @if($patronType->id == old('patron_type_id'))
                                                <option value="{{ $patronType->id }}" selected>{{ $patronType->name }}</option>
                                            @else
                                                <option value="{{ $patronType->id }}">{{ $patronType->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('patron_type_id') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->

                            <!-- col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="section_id">Section*</label>
                                    <select name="section_id" id="section_id" class="form-control select2" style="width: 100%;">
                                        @foreach($sections as $section)
                                            @if($section->id == old('section_id'))
                                                <option value="{{ $section->id }}" selected>{{ $section->name }}</option>
                                            @else
                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('section_id') }}</span>
                                </div>
                            </div>
                            <!-- col-md-4 -->
                        </div>
                        <!-- row -->

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-8 -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="image">Image (Optional)</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                </div>
                            </div>
                            <!-- col-md-8 -->
                        </div>
                        <!-- row -->
                    </div>
                    <!-- box-body -->

                    <!-- box-footer -->
                    <div class="box-footer">
                        <div class="btn-group pull-right">
                            <button type="reset" name="btnReset" id="btnReset" class="btn btn-default">Reset</button>
                            <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- box-footer -->
                </form>
            </div>
            <!-- box box-primary -->
        </div>
        <!-- col-md-12 -->
    </div>
    <!-- row -->
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
