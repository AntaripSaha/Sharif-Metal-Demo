@extends('layouts.app')
@section('css')
@endsection
@section('content')
<style>
    table {
        table-layout: fixed;
        table-width: 100%;
    }

</style>
<section class="content" id="ajaxview">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Activity Log Index</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Activity Log Index</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">All Activity List</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="accountTable" class="table table-sm table-bordered">
                                <thead>
                                    <tr class="text-center align-middle">
                                        <th style="width: 10%">Sl No</th>
                                        <th style="width: 10%">Activity Name</th>
                                        <th style="width: 10%">Activity Type</th>
                                        <th style="width: 10%">Date & Time</th>
                                        <th style="width: 60%">Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($activityLogs->count() == 0)
                                    <tr>
                                        <td colspan="5">
                                            <center>
                                                <span class="badge badge-danger">No Activity Found</span>
                                            </center>
                                        </td>
                                    </tr>
                                    @else
                                    @php
                                    $i = 1;
                                    @endphp
                                    @foreach ($activityLogs as $key => $log)
                                    <tr class="text-center">
                                        <td style="width: 10%" class="align-middle">{{ $i }}</td>
                                        <td style="width: 10%" class="align-middle">{{ $log->log_name }}</td>
                                        <td style="width: 10%" class="align-middle">{{ $log->description }}</td>
                                        <td style="width: 10%" class="align-middle">
                                            {{ $log->updated_at->toDateString() }}</td>
                                        <td style="width: 60%" class="text-left">
                                            @if ($log->description == 'created')
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table width="100%">
                                                        <tr>
                                                            <td colspan="2">
                                                                <center>
                                                                    <span class="badge badge-success">New Created
                                                                    </span>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                        @foreach ($log->properties['attributes'] as $key => $att)
                                                        <tr>
                                                            <td><strong>{{ $key }}</strong></td>
                                                            <td>{{ $att }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            @elseif($log->description == 'updated')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <table width="100%">
                                                        <tr>
                                                            <td colspan="2">
                                                                <center>
                                                                    <span class="badge badge-info">New Value</span>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                        @foreach ($log->properties['attributes'] as $key => $att)
                                                        <tr>
                                                            <td><strong>{{ $key }}</strong></td>
                                                            <td>{{ $att }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                                <div class="col-sm-6">
                                                    <table width="100%">
                                                        <tr>
                                                            <td colspan="2">
                                                                <center>
                                                                    <span class="badge badge-dark">Old Value</span>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                        @foreach ($log->properties['old'] as $key => $att1)
                                                        <tr>
                                                            <td><strong>{{ $key }}</strong></td>
                                                            <td>{{ $att1 }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            @else
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table width="100%">
                                                        <tr>
                                                            <td colspan="2">
                                                                <center>
                                                                    <span class="badge badge-danger">Deleted </span>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                        @foreach ($log->properties['attributes'] as $key => $attd)
                                                        <tr>
                                                            <td><strong>{{ $key }}</strong></td>
                                                            <td>{{ $attd }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <span class="float-right">
                                {{ $activityLogs->links() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')

@endsection
