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
                            @php $role = auth()->user()->role==1?'Admin':auth()->user()->role; @endphp
                            <h4 class="card-title mb-0">{{ ucwords(auth()->user()->name) }} ({{ $role }})</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-md-3  text-center">
                                        <div>
                                            <img for="formFile" id="profileshow" src="{{ auth()->user()->profile_pic!=''?asset('storage/'.auth()->user()->profile_pic):asset('assets/images/users/user-img.jpg') }}" class="rounded avatar-xl" alt="{{ auth()->user()->name }}">
                                            <input class="form-control" type="file" id="profilepic" name="profilepic">
                                        </div>
                                    </div>                                    
                                    {{-- <div class="col-xxl-5 col-md-6">
                                        <form method="post" action="{{ route('userupdate') }}">
                                            @csrf
                                            <div>
                                                <label for="basiInput" class="form-label mt-1">Name</label>
                                                <input type="text" class="form-control" name="name" id="basiInput" value="{{ auth()->user()->name }}" placeholder="Enter Name">
                                            </div>                                    
                                            <div>
                                                <label for="labelInput" class="form-label mt-1">Email</label>
                                                <input type="text" class="form-control" id="labelInput" name="email" value="{{ auth()->user()->email }}" placeholder="Enter Email">
                                            </div>    
                                            <div class="mt-2">
                                                <button class="btn btn-primary w-100" type="submit">Update Info</button>
                                            </div>
                                        </form>
                                    </div>   --}}
                                </div>
                            
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Change Password</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('profilepassword') }}">
                            @csrf
                            <input type="hidden" class="form-control" name="id" id="basiInput" value="{{ auth()->user()->id }}">
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
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    $('#profilepic').change(function (e) {
        const file = e.target.files[0];
        const reader = new FileReader();        
        reader.onload = function (event) {
            $('#profileshow').attr('src', event.target.result).show();
        };        
        reader.readAsDataURL(file);

        // Upload the file immediately
        let formData = new FormData();
        formData.append('profilepic', file);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('userid', {{ auth()->user()->id }});

        $.ajax({
            type: 'POST',
            url: "{{ route('userimageupload') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response.success);
            },
            error: function (xhr, status, error) {
                alert('Upload failed: ' + error);
            }
        });
    });
});

</script>
<script src="../../../assets/js/pages/password-addon.init.js"></script>
@endsection