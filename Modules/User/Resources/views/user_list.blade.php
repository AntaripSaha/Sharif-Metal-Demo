@extends('layouts.app')
@section('css')
@endsection
@section('content')
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('user.user_list')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">@lang('user.user_list')</h3>
                         @if(\Auth::user()->can('add',app('\App\User')) ||
                        Auth::user()->isOfficeAdmin())
                        {{-- Import Users Excel File Start --}}
                        <button type="button" class="ImportSellerUsers btn btn-primary btn-sm float-right ml-2">
                            <i class="fas fa-file-import"></i>
                            Import Seller's
                        </button>
                        {{-- Import Users Excel File End --}}

                        <button type="button" class="add_user btn btn-success btn-sm float-right">
                            <i class="fas fa-plus"></i>
                            @lang('user.add_user')
                        </button>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="user_list" class="table table-bordered table-striped table-sm display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Name</th>
                                    <th>Seller Code</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('js')
<script src="{{asset('js/Modules/User/user_index.js')}}"></script>
@endsection
