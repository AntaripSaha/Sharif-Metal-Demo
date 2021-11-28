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
                    <h1 class="m-0 text-dark">Product Reports</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Product Reports</li>
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
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Product Name</label>
                                <select class="form-control-sm form-control js-example-basic-single" id="product_id">
                                    <option selected disabled>Select Product</option>
                                    @foreach ($product as $p)
                                    <option value="{{ $p->product_id }}">{{ $p->product_id }} - {{ $p->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label>Company Name</label>
                                <select class="form-control-sm form-control" name="company_id" id="company_id">
                                    <option selected disabled>Select Company Name</option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <label>Price Range</label>
                                <input type="number" class="form-control form-control-sm" placeholder="Sales Start Price"
                                    name="startPrice" id="startPrice">
                            </div>

                            <div class="col-sm-2">
                                <input type="number" class="form-control form-control-sm m-top" placeholder="Sales End Price"
                                    name="endPrice" id="endPrice">
                            </div>

                            <div class="col-sm-2">
                                <button class="btn btn-info btn-sm m-top" onclick="searching()"><i class="fas fa-search"></i></button>
                                <button class="btn btn-success btn-sm m-top" onclick="refresh()"><i class="fas fa-sync-alt"></i></button>
                                <a href="{{ route('product.print_product') }}">
                                    <button class="btn btn-primary btn-sm m-top"><i class="fas fa-file-pdf"></i><span class="ml-1">PDF</span></button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="productReportTable">
                        <table id="productTable"
                            class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-left">Product Name</th>
                                    <th>Code</th>
                                    <th>Company</th>
                                    <th>ProductionPrice</th>
                                    <th>SalePrice</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $product)
                                <tr class="text-center">
                                    <td class="text-left">{{ $product->product_name }}</td>
                                    <td>{{ $product->product_id }}</td>
                                    <td>
                                        @if ($product->company_id == null)
                                        <span>-</span>
                                        @else
                                        <span>{{ $product->company[0]->name }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->production_price }}</td>
                                    <td>{{ $product->price }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{-- {{ $products->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });

    // Searching Function
    function searching() {
        var product_id = $('#product_id').val();
        var company_id = $('#company_id').val();
        var startPrice = $('#startPrice').val();
        var endPrice = $('#endPrice').val();

        if (!product_id  && !company_id  && !startPrice  && !endPrice) {
            swal("", "Please Select Product or Company or Price Range", "error");
        } else {
            $('.loading').show();
            var url = baseUrl + "product/product_searching";
            var data = {
                product_id: product_id,
                company_id: company_id,
                startPrice: startPrice,
                endPrice: endPrice
            };
            getAjaxView(url, data = data, 'productReportTable', false, 'get');
        }
    }

    // Refresh Function
    function refresh(){
        location.reload();
    }

</script>
@endsection
