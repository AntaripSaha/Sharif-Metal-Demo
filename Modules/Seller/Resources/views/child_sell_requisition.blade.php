@extends('layouts.app')
@section('css')
@endsection
@section('content')
<section class="content" id="ajaxview">
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Child Sell Requisition</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Child Sell Requisition</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">Child Sell Requisition</h3>
                            <a href="{{ route('seller.index') }}">
                                <button class="btn btn-info btn-sm float-right">Show All Requisitions</button>
                            </a>
                    </div>
                    
                    <div class="card-body">
                        <table id="childsalesTable"
                            class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>RequestDate</th>
                                    <th>Party Name</th>
                                    <th>Seller Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{asset('js/Modules/Sale/child_index.js')}}"></script>
@endsection
