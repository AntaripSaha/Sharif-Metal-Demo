@if($product == null)
<table class="table table-bordered responsive nowrap" width="100%">
    <thead>
        <tr>
            <th><input type="checkbox"></th>
            <th>@lang('product.product_name')</th>
            <th>@lang('warehouse.warehouse')</th>
            <th>@lang('warehouse.av_q')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5" class="text-center">No Product Found With this Code</td>
        </tr>
    </tbody>
</table>
@else
<table class="table table-bordered responsive nowrap" width="100%">
    <thead>
        <tr>
            {{-- <th><input type="checkbox"></th> --}}
            <th>@lang('product.product_name')</th>
            <th>@lang('warehouse.warehouse')</th>
            <th>@lang('warehouse.av_q')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            {{-- <td><input type="checkbox"></td> --}}
            <td>{{$product->products[0]->product_name}}</td>
            <td>{{$product->warehouse[0]->name}}</td>
            <td>{{$product->stck_q - $product->sell_q}}</td>
        </tr>
    </tbody>
</table>
@endif

<script type="text/javascript">
    $('.loading').hide();
</script>

