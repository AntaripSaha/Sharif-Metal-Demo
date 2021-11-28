@extends('layouts.app')
@section('css')
@endsection
@section('content')

    <style>
        .frm-control {
            width: max-content;
        }

    </style>
    <section class="content" id="ajaxview">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Change Password</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header card_buttons">
                            <h3 class="card-title">Change Your Password Here</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('user.changePasswordPost', $id) }}" method="post">
                                @csrf

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if( session()->has('error') )
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{{ session()->get('error') }}</li>
                                        </ul>
                                    </div>
                                @endif

                                @if( session()->has('success') )
                                    <div class="alert alert-success">
                                        <ul>
                                            <li>{{ session()->get('success') }}</li>
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="old_password" class="form-control"  required>
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="password" class="form-control" id="new_password" required>
                                </div>

                                <div class="form-group">
                                    <label>Re-Type New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"  required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" id="changePasswordButton">Change Password</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Password Change Policy Start -->
                <!--<div class="col-6" id="passwordPolicy">-->
                <!--    <div class="card bg-light">-->
                <!--        <div class="card-header card_buttons">-->
                <!--            <h3 class="card-title">Password Change Policy</h3>-->
                <!--        </div>-->

                <!--        <div class="card-body">-->
                <!--            <ul>-->
                <!--                <li>Password Must Have a Uppercase letter</li><br>-->
                <!--                <li>Password Must Have a Lowercase letter</li><br>-->
                <!--                <li>Password Must Have a Numeric Number</li><br>-->
                <!--                <li>Password Must Have a Special Character (@,#,$,%,^,&,*)</li><br>-->
                <!--                <li>Password Minimum length 8</li><br>-->
                <!--            </ul>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- Password Change Policy End -->
            </div>
        </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{asset('js/Modules/Bank/transaction.js')}}"></script>

    <script type="text/javascript">
        // $('#passwordPolicy').show();
        // $('#new_password').on('change', function (){
        //     const newPassword = $('#new_password').val();
        //     const passwordPattern = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        //     if(passwordPattern.test(newPassword) == true){
        //         $('#changePasswordButton').show();
        //         $('#passwordPolicy').show();
        //     }else{
        //         swal("", "Password Policy Not matched. See Password Policy.", "warning");
        //         $('#changePasswordButton').hide();
        //         $('#passwordPolicy').show();
        //         return false;
        //     }
        // });
    </script>
@endsection
