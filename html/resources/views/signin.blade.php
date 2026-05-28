<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">


<!-- Mirrored from themesbrand.com/invoika/layouts/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Aug 2024 09:23:15 GMT -->
<head>

    <meta charset="utf-8" />
    <title>Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

</head>
<style>
    .bg-img{
        background-image:url('assets/images/2.webp');
        background-size: contain;
    }
</style>
    <body class="auth-bg 100-vh bg-img">
        <div class="bg-overlay bg-light"></div>
    
        <div class="account-pages">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100 py-0 py-xl-4">    
                                    <div class="card my-auto overflow-hidden">
                                            <div class="row g-0 justify-content-center">
                                                <div class="col-md-4">
                                                    <div class="text-center p-lg-5 p-4 mt-5">
                                                        <span class="logo-lg">
                                                            <img src="{{asset('assets/images/1.webp')}}" alt="" height="200">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">                                                    
                                                    <div class="p-lg-5 p-4">
                                                        <div class="text-center">
                                                            <h5 class="mb-0 text-uppercase">Accounting And Inventory Management System</h5>
                                                            <h5 class="mb-0">(AIMS)</h5>
                                                            <p class="text-muted mt-2">Sign in to continue.</p>
                                                            @if (session()->has('mismatch'))
                                                                <span class="text-danger">{{ session('mismatch') }}</span>
                                                            @endif
                                                        </div>
                                                    
                                                        <div class="mt-4">
                                                            <form action="{{route('signin')}}" class="auth-input" method="post">
                                                                @csrf
                                                                {{-- <div class="mb-3">
                                                                    <label for="unit_name" class="form-label">Unit Name</label>
                                                                    <input type="text" name="unit_name" class="form-control" id="unit_name" placeholder="Enter Unit Name" value="{{ old('unit_name') }}">
                                                                    @if ($errors->has('unit_name'))
                                                                        <span class="text-danger">{{ $errors->first('unit_name') }}</span>
                                                                    @endif
                                                                </div>                                         --}}
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Username</label>
                                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Username" value="{{ old('name') }}">
                                                                    @if ($errors->has('name'))
                                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                                    @endif
                                                                </div>                                        
                                                                <div class="mb-2">
                                                                    <label for="userpassword" class="form-label">Password</label>
                                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                                        <input type="password" name="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input">
                                                                        @if ($errors->has('password'))
                                                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                                                        @endif
                                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                                                                   </div>
                                                                </div>                                                                
                                                                {{-- <div class="mb-3">
                                                                    <label for="question" class="form-label">Security Question: What is your raising day in dd/mm/yyyy format ?</label>
                                                                    <input type="text" name="question" class="form-control" id="question" placeholder="Eg. 10/05/1989" value="{{ old('question') }}">
                                                                    @if ($errors->has('question'))
                                                                        <span class="text-danger">{{ $errors->first('question') }}</span>
                                                                    @endif
                                                                </div>                     --}}
                                                                <div class="mt-2">
                                                                    <button class="btn btn-primary w-100" type="submit">Log In</button>
                                                                </div>
                    
                                                                
                    
                                                                {{-- <div class="mt-4 text-center">
                                                                    <p class="mb-0">Don't have an account ? <a href="signup" class="fw-medium text-primary text-decoration-underline"> Signup now </a> </p>
                                                                </div> --}}
                                                            </form>
                                                        </div>
                                    
                                                    </div>
                                                </div>
                    
                                               
                                                
                                        </div>
                                    </div>
                                    <!-- end card -->
                                    
                                    <!-- <div class="mt-5 text-center">
                                        <p class="mb-0 text-muted">©
                                            <script>document.write(new Date().getFullYear())</script> Invoika. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                                        </p>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- password-addon init -->
    <script src="assets/js/pages/password-addon.init.js"></script>

</body>

</html>
