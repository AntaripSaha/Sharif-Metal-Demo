<table id="supplier_product_view" class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>ProductName</th>
            <th>Code</th>
            <th>Model</th>
            <th>Sales Price</th>
            <th>ProductionPrice</th>
        </tr>
    </thead>

    <tbody>
        @if ($supplier_products->count() > 0)
        @foreach ($supplier_products as $products)
            <tr>
                <td>{{ $products->date }}</td>
                <td>{{ $products->category->category_name }}</td>
                <td>{{ $products->product_name }}</td>
                <td>{{ $products->product_id }}</td>
                <td>{{ $products->product_model }}</td>
                <td>{{ $products->price }}</td>
                <td>{{ $products->production_price }}</td>
            </tr>
        @endforeach
        @else
            <tr>
                <td colspan="7">
                    <center>
                        <span class="badge badge-danger">No Products Found</span>
                    </center>
                </td>
            </tr>
        @endif
        
    </tbody>
</table>

<script type="text/javascript">
    $('.loading').hide();
</script>