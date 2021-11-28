<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sharif Metal | Dashboard</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.components.css')
    @yield('css')
</head>

<body class="sidebar-mini layout-fixed accent-orange">
    <div class="wrapper">
        @include('layouts.components.navbar')
        @include('layouts.components.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-none" id="baseurl">{{ asset(' ') }}</div>
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        @include('layouts.components.footer')
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <div class="loading" id="loading"></div>
        
        <!-- /.control-sidebar -->
        <div class="modal fade modal-success" id="ajax-modal" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div style="width:96%" class="modal-dialog modal-lg">
                <div class="modal-content" id="modal-ajaxview">
                </div>
            </div>
        </div>
        
        
        <!-- MY MODAL -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <!-- MY MODAL END -->
        
        
    </div>
    <!-- ./wrapper -->
    @include('layouts.components.js')
    @yield('js')
</body>

</html>
