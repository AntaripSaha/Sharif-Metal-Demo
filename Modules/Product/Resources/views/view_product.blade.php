<div class="container bg mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card_buttons">
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="{{ route('product.index') }}"><i class="fas fa-arrow-left"></i></a>
                        </div>
                        <div class="col-sm-8">
                            <h4 class="text-center">Product Details of <span><i><u>{{ $product->product_name }}</u></i></span> </h4>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

    <!-- Vendor Details -->
{{--     <div class="row">
        <div class="col-sm-6">
            <div class="container bg card">
                <table class="table">
                    <tr>
                        <td><strong>Bank Name</strong></td>
                        <td>{{ $account_details->bank_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Account Name</strong></td>
                        <td>{{ $account_details->account_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Account Number</strong></td>
                        <td>{{ $account_details->account_no }}</td>
                    </tr>
                    <tr>
                        <td><strong>Account Type</strong></td>
                        <td>{{ $account_details->account_type }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="container bg card">
                <table class="table">
                    <tr>
                        <td><strong>Account Status</strong></td>
                        <td>
                            @if($account_details->status == '1')
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Account Holder Name</strong></td>
                        <td>{{ $account_details->account_holder_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Balance</strong></td>
                        <td><strong>৳</strong> {{ $account_details->account_balance }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div> --}}
</div>
