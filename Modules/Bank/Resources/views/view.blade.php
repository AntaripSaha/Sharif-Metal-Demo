<div class="container bg card">
    <div class="row">
        <div class="col-sm-12">
            <div class="card-header alert alert-light mt-2">
                <center>
                    <h4>Account Details of <span><i><u>{{ $account_details->bank_name }} - Ac No ({{$account_details->account_no}})</u></i></span> </h4>
                </center>
            </div>
        </div>
        <hr>
    </div>

    <!-- Vendor Details -->
    <div class="row">
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
                        <td><strong>Balance</strong></td>
                        <td><strong>৳</strong> {{ $balance }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
