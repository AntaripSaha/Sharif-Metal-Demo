<table>
    <thead>
        <!-- Header Informations Start -->
        <tr>
            <!-- Customer Name -->
            <td>Customer : </td>
            <td>{{ $customer_info->customer_name }}</td>

            <td></td>

            <!-- Seller Code & Name -->
            <td>Seller : </td>
            <td>{{ $seller_info->user_id }} - {{ $seller_info->name }}</td>

            <td></td>

            <!-- Requisition Date -->
            <td>Date : </td>
            <td>{{ $datas->v_date }}</td>

            <td></td>

            <!-- Du Amount -->
            <td>Due Amount : </td>
            <td>{{ $datas->due_amount }} BDT</td>
        </tr>
        <!-- Header Informations End -->
    </thead>
    <tbody>
        <tr></tr>
        <!-- Products Informations Start -->
        <tr>
            <td>SL No</td>
            <td>Product Code</td>
            <td>Product Name</td>
            <td>Head</td>
            <td>QTY</td>
            <td>Head</td>
            <td>Unit Price</td>
            <td>Discount(%)</td>
            <td>Amount</td>
        </tr>

        <!-- Deaclear Some Local Variable -->
        <?php
            $prd_disc = 0;
            $in_total_amount = 0;
            $i = 0;
        ?>
        @foreach ($products as $product)
        @php
            $i++;
        @endphp
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $product->products->product_id }}</td>
            <td>{{ $product->products->product_name }}</td>
            <td>{{ $product->head_code }}</td>
            <td>{{ $product->qnty }}</td>
            <td></td>
            <td>{{ $product->products->price }}</td>
            <td>{{ $product->prod_disc }}</td>
            {{-- <td>{{ $product->qnty * $product->products->price }}</td> --}}
            <td>
                @if($product->prod_disc == null || $product->prod_disc == 0)
                @php
                $prod_new_price = $product->qnty * $product->products->price;
                @endphp
                <span>{{  $prod_new_price}}</span>
                @else

                @php
                $prod_new_price = ($product->qnty * $product->products->price) - ((($product->qnty *
                $product->products->price) * $product->prod_disc) / 100);
                @endphp
                <span>{{ $prod_new_price }}</span>
                @endif
                @php
                $in_total_amount += $prod_new_price;
                @endphp
            </td>
        </tr>
        @endforeach
        <!-- Products Informations End -->
    </tbody>

    <tfoot>
        <tr>
            <td colspan="7" rowspan="2"><span><b>Transport Name :</b></span><textarea class="form-control" cols="5"
                    rows="2">{{$datas->transp_name}}</textarea></td>
            <td class="text-right"><b>Total Amount:</b></td>
            <td class="text-left">
                {{-- <input type="text" class="form-control" id="in_total_amount" value="{{ $in_total_amount }}"> --}}
                {{ $in_total_amount }}
            </td>
        </tr>

        <tr>
            <td class="text-right"><b>Sale Discount(%):</b></td>
            <td class="text-right">
                <div class="row">
                    <div class="col-sm-6">
                        @php
                        $sale_disc = $datas->sale_disc;
                        if($datas->sale_discount_overwrite != null){
                        $sale_disc = $datas->sale_discount_overwrite;
                        }else{
                        if($sale_disc == null){
                        $sale_disc = 0;
                        }else{
                        $sale_disc = $datas->sale_disc;
                        }
                        }
                        @endphp
                        <td>{{ $sale_disc }}</td>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="7" rowspan="2"><span><b>Remarks :</b></span><textarea class="form-control" cols="5"
                    rows="2">{{$datas->remarks}}</textarea></td>
            <td class="text-right"><b>Total Discount:</b></td>
            <td class="text-right">{{ $datas->discount }}</td>
        </tr>
        <tr>
            <td class="text-right"><b>Grand Total:</b></td>
            <td class="text-right">{{ $datas->amount }}</td>
        </tr>
    </tfoot>
</table>
