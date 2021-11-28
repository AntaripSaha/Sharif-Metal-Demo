<table id="stockMovementReport" class="table table-sm table-bordered  nowrap" width="100%">
    <thead>
        <tr class="text-center">
            <th>Particulars</th>
            <th>Inwards (Qnty)</th>
            <th>Outwards (Qnty)</th>
            <th>Closing Balance (Qnty)</th>
        </tr>
    </thead>
    <tbody>
        {{-- Calculate Total Start --}}
        @php
            $total_inwards = 0;
            $total_outwards = 0;   
            $total_balance_closing = 0;   
        @endphp
        {{-- Calculate Total End --}}

        @foreach ($stockMovementReport as $report)
        @php
            $total_inwards += $report['inwards'];
            $total_outwards += $report['outwards'];
            $total_balance_closing += $report['closing_balance'];
        @endphp
        <tr>
            <td>{{ $report['particulars'] }}</td>
            <td style="text-align: right">{{ $report['inwards'] }} Psc.</td>
            <td style="text-align: right">{{ $report['outwards'] }} Psc.</td>
            <td style="text-align: right">{{ $report['closing_balance'] }} Psc.</td>
        </tr>
        @endforeach

        {{-- Show Grand Total Start --}}
        <tr>
            <td style="text-align: right"><strong>Grand Total</strong></td>
            <td style="text-align: right"><strong>{{ $total_inwards }} Psc.</strong></td>
            <td style="text-align: right"><strong>{{ $total_outwards }} Psc.</strong></td>
            <td style="text-align: right"><strong>{{ $total_balance_closing }} Psc.</strong></td>
        </tr>
        {{-- Show Grand Total End --}}
    </tbody>
</table>

<script>
    $('.loading').hide();
</script>
