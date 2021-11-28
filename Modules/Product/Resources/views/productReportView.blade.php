<table id="productTable" class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
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
        @if ($products->count() == 0)
            <tr>
                <td colspan="5">
                    <center>
                        <span class="badge badge-danger">No Product Found</span>
                    </center>
                </td>
            </tr>
        @else
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
        @endif
    </tbody>
</table>

<script>
    $('.loading').hide();

</script>
