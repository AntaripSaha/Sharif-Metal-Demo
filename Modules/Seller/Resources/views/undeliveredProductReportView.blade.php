<table id="productTable" class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
    <thead>
        <tr class="text-center">
            <th>SL No</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Total Undelivered</th>
            <th>Unit Price</th>
            <th>Total Amount</th>
        </tr>
    </thead>

    <tbody>
        @php
        $i = 1;
        $in_total_amount = 0;
        @endphp
        @foreach ($undelivered_products as $undelivered)
        @if ($undelivered->undelivered_product != 0)
        <tr class="text-center">
            <td>{{ $i }}</td>
            <td>{{ $undelivered->products->product_id }}</td>
            <td>{{ $undelivered->products->product_name }}</td>
            <td>{{ $undelivered->undelivered_product }}</td>
            <td>{{ $undelivered->products->price }}</td>
            <td>{{ $undelivered->products->price * $undelivered->undelivered_product }}</td>
        </tr>
        @php
        $i++;
        $in_total_amount += ($undelivered->products->price * $undelivered->undelivered_product)
        @endphp
        @endif
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="5" class="text-right text-bold"><strong>Total Amount</strong></td>
            <td class="text-center text-bold">{{ $in_total_amount }}</td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    $('.loading').hide();
</script>
