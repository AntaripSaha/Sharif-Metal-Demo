@extends('layouts.app')
@section('css')
@endsection
@section('content')
<!-- Main content -->

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><button class="mr-2 btn-sm btn-danger" id="return_back"><i class="fa fa-arrow-left"></i></button>Sale Details of INV-{{$request_id}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Sale Details</li>
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
                    <div class="card-header card_buttons row">
                        <div class="col-md-3">
                            <img src="{{asset('img/zamanit.png')}}" class="img-fluid mt-4" alt="Company Logo">
                        </div>
                        <div class="col-md-6 text-center">
                            <h3>{{$company_info->name}}</h3>
                            <span>{{$company_info->address}}</span><br>
                            <span>{{$company_info->phone_code}}{{$company_info->phone_no}}</span>
                        </div>
                        <div class="col-md-3 text-center">
                            <p class="mt-4">@lang('layout.date'): {{$edate}}</p>
                        </div>

                    </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-3">
                            <label>
                                Customer :
                            </label>
                            <span class="text-bold">{{$cus_name}}</span>
                        </div>
                        <div class="col-lg-3">
                            <label>
                                Seller :
                            </label>
                            <span class="text-bold">{{$seller_name}}</span>
                        </div>
                        <div class="col-lg-3">
                            <label>
                                @lang('layout.date')
                            </label>
                            <span class="text-bold"> : {{$v_date}}</span>
                        </div>
                    </div>
             <form action="{{route('bill.update', $req_id)}}" method="post" >
                 @csrf
                  <div class="form-group m-form__group row">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Item Information <i
                                                class="text-danger">*</i></th>
                                        <th class="text-center">Product Code</th>
                                        <th class="text-center">Qnty <i class="text-danger">*</i></th>
                                        <th class="text-center">Rate <i class="text-danger">*</i></th>
                                        <th class="text-center">Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="reqfghghg">
                                    <?php
                                       $total = 0;
                                    ?>
                                    @foreach($req_products as $prod)
                                    <tr class="text-center">
                                        <td>
                                            <input type="hidden" name="product_name" value="{{ $prod->products->product_name }}">{{ $prod->products->product_name }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="product_id[]" value="{{ $prod->Products->product_id }}">
                                            
                                        {{ $prod->Products->product_id }}

                                        </td>
                                        <td> 
                                            <input type="number" name="del_qnt" id="del_qnt"  value="{{ $prod->del_qnt }}">
                                            
                                        </td>
                                        <td>
                                            <input type="number" name="product_price" id="product_price" value="{{ $prod->unit_price }}">
                                            </td>
                                        <td class="text-right" id="qt" onkeyup="quantity_calculate(this);">{{ $prod->del_qnt * $prod->unit_price }}</td>
                                    </tr>
                                    <?php
                                        $total += ($prod->del_qnt * $prod->unit_price);
                                    ?>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" rowspan="4"><span><b>Note :</b></span><textarea readonly class="form-control" cols="5" rows="2">{{$remarks}}</textarea></td>
                                        <td class="text-right"><b>Total Amount: </b></td>
                                        <td class="text-right">{{ $total }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b> Sale Discount(%)</b></td>
                                        <td class="text-right">
                                            @php
                                                $sale_discount = $sale_disc;
                                                if($sale_discount_overwrite != null){
                                                    $sale_discount = $sale_discount_overwrite;
                                                }else{
                                                    if($sale_discount == null){
                                                        $sale_discount = 0;
                                                    }else{
                                                        $sale_discount;
                                                    }
                                                }
                                            @endphp
                                            <span>
                                                <input style="text-align: right" type="number" name="sale_discount" id="sale_discount" value="{{ $sale_discount }}">
                                               </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>Discount:</b></td>
                                        <td class="text-right">
                                        @php
                                        $dis_amount = $sale_discount*$total/100;
                                        @endphp
                                            <span>{{$dis_amount}}</span>
                                        </td>
                                   
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>Total :</b></td>
                                        <td class="text-right">
                                            <span>{{$total-$dis_amount}}</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                        <div>
                            <button class="btn btn-success">Submit</button>
                        </div>
             </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
<style>
    input[type="number"] {
    width: 100%;
    background-color: #f5efef;
    border: none;
    text-align: center;
    }
</style>
<script>
$('#return_back').click(function() {
    location.reload();
  });
</script>
<script src="{{asset('js/Modules/Bank/transaction.js')}}"></script>


@endsection
