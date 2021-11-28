<table class="table table-bordered responsive nowrap" width="100%">
    <thead>
        <tr>
            <th>@lang('product.product_name')</th>
            <th>@lang('warehouse.warehouse')</th>
            <th>@lang('warehouse.sell_q')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($product as $product)
        <tr>
            <td>{{$product->products[0]->product_name}}</td>
            <td>{{$product->warehouse[0]->name}}</td>
            <td>{{$product->sell_q}}</td>
        </tr>
        @endforeach
    </tbody>
</table>