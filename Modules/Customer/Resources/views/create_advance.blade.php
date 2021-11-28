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
                    <h1 class="m-0 text-dark">Party Advance</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Party Advance</li>
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
                        <h3 class="card-title">Party Advance</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'customer.customer_advance','id'=>'customer-advance-form','enctype'=>"multipart/form-data"]) !!}
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label>
                                    @lang('customer.customer_name')
                                </label>
                                <select class="form-control" name="customer_id" id="customer_id">
                                    @forelse($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->customer_id}} - {{$customer->customer_name}}</option>
                                    @empty
                                    <option value="0">@lang('layout.select')</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>
                                    @lang('customer.advance_type')
                                </label>
                                <select class="form-control" name="advance_type">
                                    <option value="1">@lang('customer.payment')</option>
                                    <option value="2">@lang('customer.receive')</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <label class="">
                                    @lang('customer.amount')
                                </label>
                                <input type="number" name="amount" class="form-control m-input m-input--solid" placeholder="@lang('customer.amount')">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <button type="button" onclick="AddNewCustomer()"  class=" btn btn-success">@lang('layout.save')</button>
                        </div>
                        {!! Form::close() !!}
                        <script type="text/javascript">
                            function AddNewCustomer() {
                                var form = $('#customer-advance-form');
                                var successcallback = function (a) {
                                    toastr.success("@lang('customeer.customer_has_been_added')", "@lang('layout.success')!");
                                var url= baseUrl+"customer/index";
                                location.href = url;
                                }
                                ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
                            }
                        </script>
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
        $("#customer_id").select2()
        .on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        $('#customer_id').val(select_val);
    });
    </script>
@endsection