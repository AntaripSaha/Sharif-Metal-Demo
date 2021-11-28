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
              <h1 class="m-0 text-dark">Roles</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Roles</li>
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
                  <h3 class="card-title">Role List</h3>
                   @if(\Auth::User()->can('add',app('Modules\Role\Entities\Role')))
                    <button type="button" class="add_role btn btn-success btn-sm float-right">
                    <i class="fas fa-plus"></i>
                    @lang('layout.add')
                  </button>
                  @endif
                </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="myTable" class="table table-bordered table-striped display responsive nowrap" width="100%">
                  <thead>
                  <tr>
                    <th><input type="checkbox"></th>
                    <th>Role Name</th>
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
<script src="{{asset('js/Modules/Role/index.js')}}"></script>
@endsection