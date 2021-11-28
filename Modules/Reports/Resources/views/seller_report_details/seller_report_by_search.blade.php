<table id="sellerDetailsTable" class="table table-sm table-bordered table-striped display responsive nowrap"
    width="100%">
    <thead>
        <tr class="text-center">
            <th>Chalan No</th>
            <th>RequestDate</th>
            <th>DeliveryDate</th>
            <th>Total Amount</th>
            <th>DeliveryAmount</th>
            <th>SaleDiscount</th>
            <th>Company</th>
            <th>FullyDelivered</th>
        </tr>
    </thead>
    <tbody>
        @if ($report_details->count() > 0)
        @foreach ($report_details as $item)
        <tr class="text-center">
            <td>{{ $item->voucher_no }}</td>
            <td>{{ $item->v_date }}</td>
            <td>{{ $item->del_date }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->del_amount }}</td>
            <td>
                @if ($item->sale_disc == null)
                <span>0</span>
                @else
                <span>{{ $item->sale_disc }}</span>
                @endif
            </td>
            <td>{{ $item->company->name }}</td>
            <td>
                @if ($item->fully_delivered == 1)
                <span class="badge badge-success">Yes</span>
                @else
                <span class="badge badge-danger">No</span>
                @endif
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8">
                <center>
                    <span class="badge badge-danger">No Record Found</span>
                </center>
            </td>
        </tr>
        @endif
    </tbody>
</table>

<script type="text/javascript">
    $('.loading').hide();
</script>
