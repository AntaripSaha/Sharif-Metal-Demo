@extends('layouts.app')
@section('css')
@endsection
@section('content')
<style>
    .m-top {
        margin-top: 2rem !important;
    }

</style>
<section class="content" id="ajaxview">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Supplier Products</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
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
                        <div class="row" style="width: 100%">
                            <div class="col-md-4 mb-2">
                                <label class="d-block text-left">Select Cash</label>
                                <select class="form-control form-control-sm" id="supplier_id">
                                    <option selected disabled>Please Select Supplier Name</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="from" class="d-block text-left">@lang('layout.from') : </label>
                                <input type="date" class="form-control form-control-sm mr-sm-2" id="from" name="from">
                            </div>
                            <div class="col-md-3">
                                <label for="to" class="d-block text-left">@lang('layout.to') : </label>
                                <input type="date" class="form-control form-control-sm mr-sm-2" id="to" name="to">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-sm m-top"
                                    onclick="search()">@lang('layout.search')</button>
                                <button type="submit" class="btn btn-primary btn-sm m-top" onclick="printPdf()"
                                    id="print">@lang('layout.print')</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive" id="reportView">
                        <table id="supplier_product_view"
                            class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript">
    $("#supplier_id").select2()
        .on("select2:select", function (e) {
            var sel_element = $(e.currentTarget);
            var cus_val = sel_element.val();
            $('#supplier_id').val(cus_val);

        });

    function search() {
        // Start Loading
        $('.loading').show();
        var supplier_id = $('#supplier_id').val();
        var from = $('#from').val();
        var to = $('#to').val();

        if (supplier_id) {
            var url = baseUrl + "supplier/supplier_product_view";
            if (from && to) {
                var data = {
                    supplier_id: supplier_id,
                    from: from,
                    to: to
                };
            } else {
                var data = {
                    supplier_id: supplier_id,
                };
            }

            getAjaxView(url, data = data, 'reportView', false, 'get');

        } else {
            swal("", "Please Select Supplier Name First", "error");
        }
    }

</script>
<script src="{{asset('js/Modules/Supplier/supplier_products.js')}}"></script>
@endsection
