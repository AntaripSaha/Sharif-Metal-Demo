<table class="table table-bordered table-sm table-striped display responsive nowrap" width="100%">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Challan No</th>
            <th>Customer Name</th>
            <th>Sale By</th>
            <th>Request Date</th>
            <th>Delivery Date</th>
            {{-- <th>Price</th> --}}
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
        <tr>
            <td>{{ $sale->id }}</td>
            <td>{{$sale->voucher_no}}</td>
            <td>{{$sale->customer->customer_id}} - {{$sale->customer->customer_name}}</td>
            <td>{{$sale->seller->user_id}} - {{$sale->seller->name}}</td>
            <td>{{$sale->v_date}}</td>
            <td>{{$sale->del_date}}</td>
            {{-- <td>{{$sale->del_amount}}</td> --}}
            <td>
                <button class="btn btn-sm btn-info mr-2 printchalan-tr"
                    id="printchalan-tr-{{$sale->voucher_no}}">Challan</button>
                <button class="btn btn-sm btn-warning mr-2 print-tr" id="print-tr-{{$sale->id}}">Bill</button>
                <button class="btn btn-sm btn-success view-tr" id="view-tr-{{$sale->id}}">View</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">No Data Found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<script>
    // Stop Loading
    $('.loading').hide();
    $('.table').on('click', '.view-tr', function () {
        "use strict";
        var unitID = this.id.replace('view-tr-', '');
        var url = baseUrl + "seller/sold_details/" + unitID;
        getAjaxView(url, data = null, 'ajaxview', false, 'get');
    });
    /*Print Invoice*/
    $('.table').on('click', '.print-tr', function () {
        "use strict";
        var unitID = this.id.replace('print-tr-', '');
        var url = baseUrl + "seller/print_invoice/" + unitID;
        location.href = url;
    });

    //   Print Chalan
    $('.table').on('click', '.printchalan-tr', function () {
        "use strict";
        var unitID = this.id.replace('printchalan-tr-', '');
        var url = baseUrl + "warehouse/print_chalan/" + unitID;
        location.href = url;
    });

</script>
