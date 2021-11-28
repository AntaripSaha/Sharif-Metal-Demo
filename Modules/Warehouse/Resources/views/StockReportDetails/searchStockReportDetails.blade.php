<table id="stockReportDetailsTable" class="table table-sm table-bordered  nowrap" width="100%">
    <thead>
        <tr class="text-center">
            <th>@lang('product.product_name')</th>
            <th>@lang('warehouse.warehouse')</th>
            <th>@lang('warehouse.v_date')</th>
            <th colspan="2">@lang('warehouse.chalan_no')</th>
            <th>@lang('warehouse.stq_q')</th>
            <th>@lang('warehouse.sell_q')</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_in_qnty = 0;
            $total_out_qnty = 0;
        @endphp
        @foreach ($stock_report_details as $product)
        <tr class="text-center">
            <td class="align-middle" rowspan="{{ $product->products[0]->warehouse_insert->whereBetween('v_date', [$from, $to])->where('warehouse_id',$warehouse_id)->count() + 1 }}">
                {{ $product->products[0]->product_name }} - {{ $product->products[0]->product_id }} - {{ $product->products[0]->head_code }}</td>

            @if( $product->products[0]->warehouse_insert->first()->warehouse[0]->id == $warehouse_id )
            <td>
                {{ $product->products[0]->warehouse_insert->first()->warehouse[0]->name }}
            </td>
            <td>
                {{ $product->products[0]->warehouse_insert->first()->v_date }}
            </td>
            <td colspan="2">{{ $product->products[0]->warehouse_insert->first()->chalan_no }}</td>
            <td>{{ $product->products[0]->warehouse_insert->first()->in_qnt }}</td>
            <td>{{ $product->products[0]->warehouse_insert->first()->out_qnt }}</td>
            @php
                $total_in_qnty += $product->products[0]->warehouse_insert->first()->in_qnt;
                $total_out_qnty += $product->products[0]->warehouse_insert->first()->out_qnt;
            @endphp
            @else
            <td colspan="9"></td>
            @endif
            
        </tr>
        @foreach( $product->products[0]->warehouse_insert->whereBetween('v_date', [$from, $to])->where("warehouse_id",$warehouse_id) as $key => $warehouse )
            @if( $key > 0 )
            <tr class="text-center">
                <td>{{ $warehouse->warehouse[0]->name }}</td>
                <td>{{ $warehouse->v_date }}</td>
                <td colspan="2">{{ $warehouse->chalan_no }}</td>
                <td>{{ $warehouse->in_qnt }}</td>
                <td >{{ $warehouse->out_qnt }}</td>
            </tr>
                @php
                    $total_in_qnty += $warehouse->in_qnt;
                    $total_out_qnty += $warehouse->out_qnt;
                @endphp
            @endif
        @endforeach
        <tr>
            <td colspan="4" class="text-right text-bold">Total</td>
            <td class="text-center text-bold">{{ $total_in_qnty }}</td>
            <td class="text-center text-bold">{{ $total_out_qnty }}</td>
        </tr>
        <tr>
            <td colspan="4" class="text-right text-bold">Available</td>
            <td colspan="3" class="text-center text-bold">{{ $total_in_qnty - $total_out_qnty }}</td>
        </tr>
            @php
                $total_in_qnty = 0;
                $total_out_qnty = 0;
            @endphp
        @endforeach

    </tbody>
</table>

<script>
    $('.loading').hide();
</script>
