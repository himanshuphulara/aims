@extends('layouts.master')
@section('maincontent')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }}</h4>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">{{ ucfirst($user->name) }}</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('userupdate') }}">
                            <div class="row gy-4 justify-content-center">
                                @csrf
                                <input type="hidden" class="form-control" name="id" id="basiInput" value="{{ $user->id }}">
                                <div class="col-xxl-5 col-md-6">
                                    {{-- <div>
                                        <label for="unit_name" class="form-label">Unit Name</label>
                                        <input type="text" name="unit_name" class="form-control" id="unit_name" value="{{ $user->unit_name }}" placeholder="Enter Unit Name" required>
                                    </div> --}}
                                    <div>
                                        <label for="basiInput" class="form-label mt-1">Username</label>
                                        <input type="text" class="form-control" name="name" id="basiInput" value="{{ $user->name }}" placeholder="Enter Name" required>
                                    </div>
                                
                                    {{-- <div>
                                        <label for="labelInput" class="form-label mt-1">Email</label>
                                        <input type="text" class="form-control" id="labelInput" name="email" value="{{ $user->email }}" placeholder="Enter Email" required>
                                    </div> --}}
                                    {{-- <div>
                                        <label for="question" class="form-label">Security Question: What is your raising day in dd/mm/yyyy format ?</label>
                                        <input type="text" name="question" class="form-control" id="question" value="{{ $user->question }}" placeholder="Eg. 10/05/1989" value="{{ old('question') }}" required>
                                    </div> --}}
                                    <div>
                                        <label for="role" class="form-label">Role</label>
                                        <select name="role" id="role" class="form-control" required>
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" @if($userrole==$role->name) selected @endif>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2">
                                        <button class="btn btn-primary w-100 text-uppercase" type="submit">Update Info</button>
                                    </div>
                                </div>  
                            </div>
                            </form>
                            <!--end row-->
                        </div>                       
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Change Password</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('userpassword') }}">
                            @csrf
                            <input type="hidden" class="form-control" name="id" id="basiInput" value="{{ $user->id }}">
                            <div class="row gy-4 justify-content-center">
                                <div class="col-xxl-5 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label mt-1">New Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                        <input type="password" name="password" class="form-control pe-5 password-input" id="password-input" placeholder="Enter New Password" required>
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <button class="btn btn-primary w-100 text-uppercase" type="submit">Update Password</button>
                                    </div>
                                </div>  
                            </div>
                            </form>
                            <!--end row-->
                        </div>                       
                    </div>
                </div>
                <!--end col-->
            </div>

        </div>
    </div>
</div>
            <!-- end page title -->
<script src="../../../assets/js/pages/password-addon.init.js"></script>
@endsection