@extends('patron-web.layouts.default-layout')

@section('title', 'Penalty')

@section('content-header')
    <h1>Penalty Record</h1>
    <ol class="breadcrumb">
        <li><p><i class="fa fa-home"></i> Home</p></li>
        <li class="active"><a href="{{ route('patron-web.penalties.index') }}">Penalty</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Penalty List</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="penaltiesTable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Accession No.</th>
                                    <th class="text-center">Book Title</th>

                                    <th class="text-center">Borrow Date</th>
                                    <th class="text-center">Return Date</th>
                                    <th class="text-center">Due Date</th>

                                    <th class="text-center">Overdue (Days)</th>
                                    <th class="text-center">Penalty Amount</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penalties as $penalty)
                                    <tr>
                                        <td>{{ $penalty->accession->accession_no }}</td>
                                        <td>{{ $penalty->accession->book->title }}</td>

                                        <td>{{ $penalty->return_book->borrow->borrow_date }}</td>
                                        <td>{{ $penalty->return_book->return_date }}</td>
                                        <td>{{ $penalty->penalty_due_date }}</td>

                                        <td>{{ $penalty->overdue }}</td>
                                        <td>{{ $penalty->amount }}</td>
                                        <td>
                                            @if($penalty->status === 1)
                                                <span class="label label-warning">Unpaid</span>
                                            @else
                                                <span class="label label-success">Paid</span>
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
        $('#penaltiesTable').DataTable();
    </script>
@endpush
