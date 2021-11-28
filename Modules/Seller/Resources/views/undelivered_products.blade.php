<!-- Main content -->

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Undelivered Products of Request - {{$req_id}}</h1>
                <input type="hidden" id="req_id" value="{{$req_id}}">
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Undelivered Products</li>
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
                    <h3 class="card-title">Undelivered Products</h3>
                    <button class="btn btn-sm btn-info float-right" id="un_product"><i class="fas fa-arrow-left"></i></button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4">
                            <label>
                                Customer :
                            </label>
                            <span class="text-bold">{{$req_details->customer->customer_name}}</span>
                        </div>
                        <div class="col-lg-4">
                            <label>
                                Seller :
                            </label>
                            <span class="text-bold">{{$req_details->seller->name}}</span>
                        </div>
                        <div class="col-lg-4">
                            <label>
                                @lang('layout.date')
                            </label>
                            <span class="text-bold"> : {{$req_details->v_date}}</span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="table-responsive asked_q">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Item Information</th>
                                        <th class="text-center">Asked Qnt</th>
                                        <th class="text-center">Delivered Qnt</th>
                                        <th class="text-center">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody id="reqfghghg">
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->products->product_name}}</td>
                                        <td>{{ $product->qnty}}</td>
                                        <td>{{ $product->del_qnt }}</td>
                                        <td>{{ $product->qnty - $product->del_qnt }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-md btn-success" id="re_order">Re Order</button>
                            
                            <!-- Only For Challan User -->
                            @if( Auth::user()->role->id == 18 ) 
                            <a href="{{ route('seller.undelivered_details.approve',$req_details->id) }}" class="btn btn-md btn-success">Approve Order</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->

<script>
    $('.card_buttons').on('click', '#un_product', function(){
      "use strict";
      location.reload();
    });

    $('.asked_q').on('click', '#re_order', function(){
      "use strict";
      var unitID  = $('#req_id').val();
      var content = 'Reorder the remaining items';
      var confirmtext = 'Order Again';
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Reordered Successfully  ', 'Success');
              location.reload();
              }else{
              toastr.error('Error !! Try again !!');
              }
          }

        var url= baseUrl+"seller/re_order/"+unitID;
      
        ajaxGetRequest(url,requestCallback);
      }
      confirmAlert(confirmCallback,content,confirmtext)
  });

</script>