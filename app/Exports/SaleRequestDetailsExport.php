<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SaleRequestDetailsExport implements FromView
{
    public $data, $products, $customer_info, $seller_info;

    public function view(): View{
        return view('export.sale_request', [
            'datas'         => $this->data,
            'products'      => $this->products,
            'customer_info' => $this->customer_info,
            'seller_info'   => $this->seller_info
        ]);
    }
    public function getDownloadByQuery($data, $products, $customer_info, $seller_info){
        $this->data          = $data;
        $this->products      = $products;
        $this->customer_info = $customer_info;
        $this->seller_info   = $seller_info;
        return $this;
    }
}
