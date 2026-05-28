<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>

    <meta charset="utf-8" />
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico')}}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

</head>

    <body class="auth-bg 100-vh">
        <div class="bg-overlay bg-light"></div>
    
        <div class="account-pages">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100 py-0 py-xl-4">
    
                                    <!-- <div class="text-center mb-5">
                                        <a href="index.html">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/logo-dark.png')}}" alt="" height="21">
                                            </span>
                                        </a>
                                    </div> -->
    
                                    <div class="card my-auto overflow-hidden">
                                            <div class="row g-0 justify-content-center">
                                                <div class="col-lg-6">
                                                    <div class="p-lg-5 p-4">
                                                        <div class="text-center">
                                                            <h5 class="mb-0">Create New Account</h5>
                                                            @if (session()->has('success'))
                                                                <span class="text-success">{{ session('success') }}</span>
                                                            @endif
                                                        </div>
                                                    
                                                        <div class="mt-4">
                                                            <form action="{{route('signup')}}" class="auth-input" method="post">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <input type="text" name="email" class="form-control" id="email" placeholder="Enter email">
                                                                </div>
                            
                                                                <div class="mb-3">
                                                                    <label for="username" class="form-label">Username</label>
                                                                    <input type="text" name="name" class="form-control" id="username" placeholder="Enter username">
                                                                </div>
                                        
                                                                <div class="mb-2">
                                                                    <label for="userpassword" class="form-label">Password</label>
                                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                                        <input type="password" name="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input">
                                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                                                                   </div>
                                                                </div>
                    
                                                               
                    
                                                                <div class="mt-2">
                                                                    <button class="btn btn-primary w-100" type="submit">Sign Up</button>
                                                                </div>
                    
                                                                
                    
                                                                <div class="mt-4 text-center">
                                                                    <p class="mb-0">Have an account ? <a href="/" class="fw-medium text-primary text-decoration-underline"> Signin </a> </p>
                                                                </div>
                                                            </form>
                                                        </div>
                                    
                                                    </div>
                                                </div>
                    
                                                
                                                
                                        </div>
                                    </div>
                                    <!-- end card -->
                                    
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
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins.js')}}"></script>
    <script src="assets/js/pages/password-addon.init.js"></script>

</body>

</html>