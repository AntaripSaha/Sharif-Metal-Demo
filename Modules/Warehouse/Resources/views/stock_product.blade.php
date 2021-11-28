@if($product == null)
<table class="table table-bordered responsive nowrap" width="100%">
    <thead>
        <tr>
            <th>@lang('product.product_name')</th>
            <th>@lang('warehouse.warehouse')</th>
            <th>@lang('warehouse.stq_q')</th>
            <th>@lang('warehouse.sell_q')</th>
            <th>@lang('warehouse.av_q')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="7" class="text-center">No Product Found With this Code</td>
        </tr>
    </tbody>
</table>
@else
<table class="table table-bordered responsive nowrap" width="100%">
    <thead>
        <tr>
            <th>@lang('product.product_name')</th>
            <th>@lang('warehouse.warehouse')</th>
            <th>@lang('warehouse.stq_q')</th>
            <th>@lang('warehouse.sell_q')</th>
            <th>@lang('warehouse.av_q')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$product->products[0]->product_name}} - {{$product->products[0]->head_code}}</td>
            <td>{{$product->warehouse[0]->name}}</td>
            <td>{{$product->stck_q}}</td>
            <td>{{$product->sell_q}}</td>
            <td>{{$product->stck_q - $product->sell_q}}</td>
        </tr>
    </tbody>
</table>
@endif

<script type="text/javascript">
    $('.loading').hide();
</script>


