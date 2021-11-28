@extends('layouts.app')
@section('css')
@endsection
@section('content')
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('menu.Cheques In Hand')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('menu.Cheques In Hand')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">@lang('menu.Cheques In Hand')</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="salesTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Cheque Id</th>
                                    <th>Customer Name</th>
                                    <th>Issued Date</th>
                                    <th>Mature Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checks as $check)
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>{{$check->check_no}}</td>
                                        <td>{{$check->customer->customer_name}}</td>
                                        <td>{{$check->VDate}}</td>
                                        <td>{{$check->mat_date}}</td>
                                        <td>@if($check->is_credited == 0) {{ 'Waiting'}} @else {{ 'Bounced' }}@endif</td>
                                        <td>
                                            @if($tdate == $check->mat_date)
                                                <a class="mr-2 cp view-tr btn btn-success btn-sm" id="credit" onclick="mark_credited({{$check->id}})"> Credit Today</a>
                                                <a class="cp view-tr btn btn-danger btn-sm" id="bounced" onclick="mark_bounced({{$check->id}})"> Mark Bounced</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('js')
<script>
    function mark_credited(check_id) {
        var status = 1;
        var url = baseUrl+"accounts/check_update/"+check_id+"/"+status;
        var content = '';
        var confirmtext = 'Confirm Check Granted';
        var confirmCallback=function(){
        var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Check Updated Successfully', 'Success');
              location.href = baseUrl+"accounts/cr_check/"+check_id;
              }else{
              toastr.error('Error !! Try again !!');
              }
          }
      
          ajaxGetRequest(url,requestCallback);
      }
      confirmAlert(confirmCallback,content,confirmtext)
    }
    function mark_bounced(check_id) {
        var status = 0;
        console.log(check_id);
    }



</script>
@endsection
