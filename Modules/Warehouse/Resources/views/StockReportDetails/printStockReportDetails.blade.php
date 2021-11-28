<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <title>Print Stock Report Details</title>
</head>

<body style="width: 100%;margin: auto;">
    <div class="card">
        <div class="card-header">
            <div class="col-md-6" style="text-align: center;margin-top: -20px;">
                <p
                    style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:38%;">
                    <span style="font-size:22px;"><b>{{$title}}</b></span></p><br>
                <span style="font-size: 30px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
            </div>
            <hr>
            <div class="col-md-12">
                <span style="font-size: 18px; text-align: left;float: left; width: 60%">Date
                    <span style="margin-left: 42%;">:</span>
                    @if( $from && $to )
                        {{ $from }} - {{ $to }}
                    @else 
                        All
                    @endif
                    </span>
                <br>
            </div>

            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 18px; width: 60%">Warehouse Name <span
                        style="margin-left: 20%;">:</span>
                    <span style="font-size: 18px;">
                    @if( $warehouse_data )
                        {{ $warehouse_data->name }}
                    @else
                        All
                    @endif
                </span>
                </span>
                <span style="text-align: right;float: right;font-size: 18px;"></span><br>
            </div>

            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 18px; width: 60%">Product Name
                    <span style="margin-left: 25%;">:</span> All</span>
            </div>
        </div>
        <br><br><br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <div class="col-12">
                <div class="card-body table-responsive" id="stockReportDetails">
                    <table id="stockReportDetailsTable" class="table table-sm table-bordered  nowrap" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th>@lang('product.product_name')</th>
                                <th>@lang('warehouse.warehouse')</th>
                                <th>@lang('warehouse.v_date')</th>
                                <th>@lang('warehouse.chalan_no')</th>
                                <th>@lang('warehouse.stq_q')</th>
                                <th>@lang('warehouse.sell_q')</th>
                            </tr>
                        </thead>

                        @if( $warehouse_data )
                            @if( $from && $to )
                            <tbody>
                                @php
                                $total_in_qnty = 0;
                                $total_out_qnty = 0;
                                $available = 0;
                                @endphp

                                    @foreach ($stock_report_details as $product)
                                    <tr>
                                        @php
                                            $count = $product->products[0]->warehouse_insert->whereBetween('v_date',[$from,$to])->where('warehouse_id',$warehouse_data->id)->count();
                                        @endphp
                                        <td class="align-middle" rowspan="{{ $count }}">
                                            {{ $product->products[0]->product_name }} - {{ $product->products[0]->product_id }} - {{ $product->products[0]->head_code }}
                                        </td>
                                            
                                        @if( $product->products[0]->warehouse_insert->first()->warehouse[0]->id == $warehouse_data->id )
                                        @php
                                            $warehouse_insert = $product->products[0]->warehouse_insert->first();
                                        @endphp
                                        <td>
                                            {{ $warehouse_data->name }}
                                        </td>
                                        <td>
                                            {{ $warehouse_insert->v_date }}
                                        </td>
                                        <td>{{ $warehouse_insert->chalan_no }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->in_qnt }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->out_qnt }}</td>
                                        @php
                                            $total_in_qnty += $warehouse_insert->in_qnt;
                                            $total_out_qnty += $warehouse_insert->out_qnt;
                                        @endphp
                                        @else
                                        <td colspan="5"></td>
                                        @endif
                                        
                                    </tr>

                                    @foreach( $new_data[$product->product_id] as $key => $warehouse )
                                        @if( $key > 0 )
                                        <tr class="text-center">
                                            <td>{{ $warehouse['name'] }}</td>
                                            <td>{{ $warehouse['v_date'] }}</td>
                                            <td>{{ $warehouse['chalan_no'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['in_qnt'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['out_qnt'] }}</td>
                                        </tr>
                                            @php
                                                $total_in_qnty += $warehouse['in_qnt'];
                                                $total_out_qnty += $warehouse['out_qnt'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_in_qnty }}</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_out_qnty }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Available</td>
                                        <td colspan="2" style="text-align: center; font-weight: bold;">{{ $total_in_qnty - $total_out_qnty }}</td>
                                    </tr>
                                    @php
                                    $total_in_qnty = 0;
                                    $total_out_qnty = 0;
                                    $available = 0;

                                    @endphp
                                    @endforeach

                            </tbody>
                            @else
                            <tbody>
                                @php
                                $total_in_qnty = 0;
                                $total_out_qnty = 0;
                                $available = 0;
                                @endphp

                                    @foreach ($stock_report_details as $product)
                                    <tr>
                                        <td class="align-middle" rowspan="{{ $product->products[0]->warehouse_insert->where('warehouse_id',$warehouse_data->id)->count()}}">
                                            {{ $product->products[0]->product_name }} - {{ $product->products[0]->product_id }} - {{ $product->products[0]->head_code }}
                                        </td>
                                            
                                        @if( $product->products[0]->warehouse_insert->first()->warehouse[0]->id == $warehouse_data->id )
                                        @php
                                            $warehouse_insert = $product->products[0]->warehouse_insert->first();
                                        @endphp
                                        <td>
                                            {{ $warehouse_data->name }}
                                        </td>
                                        <td>
                                            {{ $warehouse_insert->v_date }}
                                        </td>
                                        <td>{{ $warehouse_insert->chalan_no }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->in_qnt }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->out_qnt }}</td>
                                        @php
                                            $total_in_qnty += $warehouse_insert->in_qnt;
                                            $total_out_qnty += $warehouse_insert->out_qnt;
                                        @endphp
                                        @else
                                        <td colspan="5"></td>
                                        @endif
                                        
                                    </tr>

                                    @foreach( $new_data[$product->product_id] as $key => $warehouse )
                                        @if( $key > 0 )
                                        <tr class="text-center">
                                            <td>{{ $warehouse['name'] }}</td>
                                            <td>{{ $warehouse['v_date'] }}</td>
                                            <td>{{ $warehouse['chalan_no'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['in_qnt'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['out_qnt'] }}</td>
                                        </tr>
                                            @php
                                                $total_in_qnty += $warehouse['in_qnt'];
                                                $total_out_qnty += $warehouse['out_qnt'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_in_qnty }}</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_out_qnty }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Available</td>
                                        <td colspan="2" style="text-align: center; font-weight: bold;">{{ $total_in_qnty - $total_out_qnty }}</td>
                                    </tr>
                                    @php
                                    $total_in_qnty = 0;
                                    $total_out_qnty = 0;
                                    $available = 0;

                                    @endphp
                                    @endforeach

                                


                            </tbody>
                            @endif
                        @else

                            @if( $from && $to )
                            <tbody>
                                @php
                                $total_in_qnty = 0;
                                $total_out_qnty = 0;
                                $available = 0;
                                @endphp

                                    @foreach ($stock_report_details as $product)
                                    <tr>
                                        <td class="align-middle" rowspan="{{ $product->products[0]->warehouse_insert->whereBetween('v_date',[$from,$to])->pluck('id')->count() }}">
                                            {{ $product->products[0]->product_name }} - {{ $product->products[0]->product_id }} - {{ $product->products[0]->head_code }}
                                        </td>
                                            
                                        @php
                                            $warehouse_insert = $product->products[0]->warehouse_insert->first();
                                        @endphp
                                        <td>
                                            {{ $warehouse_insert->warehouse[0]->name }}
                                        </td>
                                        <td>
                                            {{ $warehouse_insert->v_date }}
                                        </td>
                                        <td>{{ $warehouse_insert->chalan_no }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->in_qnt }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->out_qnt }}</td>
                                        @php
                                            $total_in_qnty += $warehouse_insert->in_qnt;
                                            $total_out_qnty += $warehouse_insert->out_qnt;
                                        @endphp
                                        
                                        
                                    </tr>

                                    @foreach( $new_data[$product->product_id] as $key => $warehouse )
                                        @if( $key > 0 )
                                        <tr class="text-center">
                                            <td>{{ $warehouse['name'] }}</td>
                                            <td>{{ $warehouse['v_date'] }}</td>
                                            <td>{{ $warehouse['chalan_no'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['in_qnt'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['out_qnt'] }}</td>
                                        </tr>
                                            @php
                                                $total_in_qnty += $warehouse['in_qnt'];
                                                $total_out_qnty += $warehouse['out_qnt'];
                                            @endphp
                                        @endif
                                    @endforeach

                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_in_qnty }}</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_out_qnty }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Available</td>
                                        <td colspan="2" style="text-align: center; font-weight: bold;">{{ $total_in_qnty - $total_out_qnty }}</td>
                                    </tr>
                                    @php
                                    $total_in_qnty = 0;
                                    $total_out_qnty = 0;
                                    $available = 0;

                                    @endphp
                                    @endforeach

                                


                            </tbody>
                            @else
                            <tbody>
                                @php
                                $total_in_qnty = 0;
                                $total_out_qnty = 0;
                                $available = 0;
                                @endphp

                                    @foreach ($stock_report_details as $product)
                                    <tr>
                                        <td class="align-middle" rowspan="{{ $product->products[0]->warehouse_insert->count() }}">
                                            {{ $product->products[0]->product_name }} - {{ $product->products[0]->product_id }} - {{ $product->products[0]->head_code }}
                                        </td>
                                            
                                        @php
                                            $warehouse_insert = $product->products[0]->warehouse_insert->first();
                                        @endphp
                                        <td>
                                            {{ $warehouse_insert->warehouse[0]->name }}
                                        </td>
                                        <td>
                                            {{ $warehouse_insert->v_date }}
                                        </td>
                                        <td>{{ $warehouse_insert->chalan_no }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->in_qnt }}</td>
                                        <td style="text-align: center">
                                            {{ $warehouse_insert->out_qnt }}</td>
                                        @php
                                            $total_in_qnty += $warehouse_insert->in_qnt;
                                            $total_out_qnty += $warehouse_insert->out_qnt;
                                        @endphp
                                        
                                        
                                    </tr>

                                    @foreach( $new_data[$product->product_id] as $key => $warehouse )
                                        @if( $key > 0 )
                                        <tr class="text-center">
                                            <td>{{ $warehouse['name'] }}</td>
                                            <td>{{ $warehouse['v_date'] }}</td>
                                            <td>{{ $warehouse['chalan_no'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['in_qnt'] }}</td>
                                            <td style="text-align: center">{{ $warehouse['out_qnt'] }}</td>
                                        </tr>
                                            @php
                                                $total_in_qnty += $warehouse['in_qnt'];
                                                $total_out_qnty += $warehouse['out_qnt'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_in_qnty }}</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $total_out_qnty }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Available</td>
                                        <td colspan="2" style="text-align: center; font-weight: bold;">{{ $total_in_qnty - $total_out_qnty }}</td>
                                    </tr>
                                    @php
                                    $total_in_qnty = 0;
                                    $total_out_qnty = 0;
                                    $available = 0;

                                    @endphp
                                    @endforeach

                                


                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
