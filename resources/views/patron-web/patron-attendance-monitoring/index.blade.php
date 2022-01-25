@extends('patron-web.layouts.default-layout')

@section('title', 'Attendance Monitoring')

@section('content-header')
    <h1>Attendance Monitoring Record</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-home"></i> Home</p></li>
        <li class="active"><a href="{{ route('patron-web.patron-attendance-monitoring.index') }}">Attendance Monitoring</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Attendance Monitoring List</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="patronAttendanceLogsTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Date In</th>
                                    <th class="text-center">Time In</th>
                                    <th class="text-center">Date Out</th>
                                    <th class="text-center">Time Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patronAttendanceLogs as $patronAttendanceLog)
                                    <tr>
                                        <td>{{ $patronAttendanceLog->date_in }}</td>
                                        <td>{{ $patronAttendanceLog->time_in}}</td>
                                        <td>{{ $patronAttendanceLog->date_out }}</td>
                                        <td>{{ $patronAttendanceLog->time_out }}</td>
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
        $('#patronAttendanceLogsTable').DataTable();
    </script>
@endpush
