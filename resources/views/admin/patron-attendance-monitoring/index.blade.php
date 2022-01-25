@extends('admin.layouts.default-layout')

@section('title', 'Patron Attendance Monitoring')

@section('content-header')
    <h1>Attendance Monitoring</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-users"></i> Attendance Monitoring</p></li>
        <li class="active"><a href="{{ route('patron-attendance-monitoring.index') }}">Patron Attendance Monitoring</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Patron Attendance Monitoring</h3>
                </div>

                <div class="box-body">
                    @include('admin.component.errors-and-messages')

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <img name="image" id="image" class="img-responsive">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="patronNo">Enter Patron No.</label>
                                    <div class="input-group">
                                        <input type="text" name="patronNo" id="patronNo" class="form-control" autocomplete="off">
                                        <div class="input-group-btn">
                                            <button type="button" name="btnScan" id="btnScan" class="btn btn-default">Scan</button>
                                        </div>
                                    </div>
                                    <span id="patronNoError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateIn">Date In:</label>
                                        <span name="dateIn" id="dateIn" class="form-control attendance-log-form"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateOut">Date Out:</label>
                                        <span name="dateOut" id="dateOut" class="form-control attendance-log-form"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="timeIn">Time In:</label>
                                        <span name="timeIn" id="timeIn" class="form-control attendance-log-form"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="timeOut">Time Out:</label>
                                        <span name="timeOut" id="timeOut" class="form-control attendance-log-form"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="patronName">Patron Name:</label>
                                        <span name="patronName" id="patronName" class="form-control attendance-log-form"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">Date:</label>
                                <span name="date" id="date" class="form-control">{{ date('Y-m-d') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="time">Time:</label>
                                <span name="time" id="time" class="form-control"></span>
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
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            $('#btnScan').click(function() {
                $('#patronNo').trigger('blur')
            });

            $('#patronNo').blur(function() {
                clearForm()
                $('#btnScan').attr({disabled : true})

                $.ajax({
                    type : 'POST',
                    url : '/patron-attendance-monitoring',
                    data : {'_token' : CSRF_TOKEN, 'patron_no' : $(this).val()},
                    success : (response) => {
                        $('#btnScan').attr({disabled : false})

                        switch(response.status) {
                            case 'login':
                                $(this).val('')
                                $(this).select()
                                $('#dateIn').html(response.data.date_in)
                                $('#timeIn').html(response.data.time_in)
                                $('#patronName').html(response.data.patron.name)
                                $('#image').attr({src : `${response.data.patron.image}`})
                            break;

                            case 'logout':
                                $(this).val('')
                                $(this).select()
                                $('#dateIn').html(response.data.date_in)
                                $('#dateOut').html(response.data.date_out)
                                $('#timeIn').html(response.data.time_in)
                                $('#timeOut').html(response.data.time_out)
                                $('#patronName').html(response.data.patron.name)
                                $('#image').attr({src : `${response.data.patron.image}`})
                            break;
                        }
                    },
                    error : (error) => {
                        $('#patronNoError').html(error.responseJSON.errors.patron_no)
                        $('#btnScan').attr({disabled : false})
                        $(this).select()
                    }
                });
            });
        });

        function clearForm() {
            $('.img-responsive').attr({src : ''})
            $('.attendance-log-form').html('')
            $('.text-danger').html('')
        }

        realTime()
        function realTime() {
            setInterval(() => {
                let date = new Date()

                let hours = date.getHours()
                hours = hours % 12
                hours = hours != 0 ? (hours > 9 ? hours : '0' + hours) : 12

                let minutes = date.getMinutes()
                minutes = minutes > 9 ? minutes : '0' + minutes

                let seconds = date.getSeconds()
                seconds = seconds > 9 ? seconds : '0' + seconds

                let timeAbbreviation = date.getHours() >= 12 && date.getHours() <= 23 ? 'PM' : 'AM'
                let strTime = hours + ':' + minutes + ':' + seconds + ' ' + timeAbbreviation
                $('#time').html(strTime)
            }, 1000)
        }
    </script>
@endpush
