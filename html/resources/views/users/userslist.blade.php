@extends('layouts.master')
@section('maincontent')
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            @php 
                $button = auth()->user()->hasPermissions('useradd')?'Add User':''; 
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{$userslist->total()}}" target="adduser" currentmonth=""/>
            <!-- start page title -->
            {{-- <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }} ({{$userslist->total()}})</h4>
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center float-end">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#adduser"><i class="las la-plus me-1"></i> Add User</button>
                    </div>
                </div>
                @endif
            </div> --}}
            <!-- end page title -->

            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th scope="col">User ID</th>
                                            <th scope="col">User Name</th>
                                            {{-- <th scope="col">Unit Name</th> --}}
                                            {{-- <th scope="col" style="width: 20%;">Email</th> --}}
                                            <th scope="col" style="width: 20%;">Profile</th>
                                            <th scope="col">Role</th>
                                            <th scope="col" class="w-50">Permissions</th>
                                            {{-- <th scope="col" class="w-50">Security Key</th> --}}
                                            <th scope="col">Date</th>
                                            @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody>@php $i=1; @endphp
                                        @forelse($userslist as $user)
                                        @php 
                                            $userrole = $user->roles[0]->name; 
                                            $role =  $user->roles->first();
                                            $existingPermissions = $role->permissions->pluck('name')->toArray();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ids[]" id="check1" value="{{ $user->id }}">
                                                </div>
                                            </td>
                                            <td><p class="fw-medium mb-0">{{ $user->id }}</p></td>
                                            <td>{{ $user->name }}</td>
                                            {{-- <td>{{ $user->unit_name??'-' }}</td> --}}
                                            {{-- <td>{{ $user->email }}</td> --}}
                                            <td><img class="rounded-circle header-profile-user" src="{{ $user->profile_pic!=''?asset('storage/'.$user->profile_pic):asset('assets/images/users/user-img.jpg') }}" alt="Header Avatar"></td>
                                            <td>{{ $userrole }}</td>
                                            <td style="word-wrap: break-word;white-space:normal;">
                                                @foreach($existingPermissions as $per)
                                                <span class="badge text-bg-secondary">{{ $per }}</span>
                                                @endforeach
                                            </td>
                                            {{-- <td>{{ $user->question??'-' }}</td> --}}
                                            <td>{{ $user->created_at }}</td>
                                            {{-- <td>$240.00</td> --}}
                                            @if(auth()->user()->role==1)
                                            <td>
                                                <a class="btn btn-primary btn-sm get-data" href="{{ route('useredit',[$user->id]) }}">
                                                    <i class="las la-pen-alt fs-18 align-middle"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm" href="{{ route('userdelete',[$user->id]) }}">
                                                    <i class="las la-trash-alt fs-18 align-middle"></i> Delete
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr><td colspan=7 class="text-center">No Data Found</td></tr>
                                        @endforelse
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                </div>
            </div>

            
                {{ $userslist->links() }}
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<!-- Modal -->
<div class="modal fade" id="adduser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('useradd')}}" class="auth-input" method="post">
                @csrf
                {{-- <div class="mb-3">
                    <label for="unit_name" class="form-label">Unit Name</label>
                    <input type="text" name="unit_name" class="form-control" id="unit_name" placeholder="Enter Unit Name" required>
                </div> --}}
                {{-- <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Enter email" required>
                </div> --}}

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="name" class="form-control" id="username" placeholder="Enter username" required>
                </div>

                <div class="mb-2">
                    <label for="userpassword" class="form-label">Password</label>
                    <div class="position-relative auth-pass-inputgroup mb-3">
                        <input type="password" name="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input" required>
                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                   </div>
                </div>
                {{-- <div class="mb-3">
                    <label for="question" class="form-label">Security Question: What is your raising day in dd/mm/yyyy format ?</label>
                    <input type="text" name="question" class="form-control" id="question" placeholder="Eg. 10/05/1989" value="{{ old('question') }}">
                </div> --}}
                <div class="mb-3">
                    <label for="username" class="form-label">Role</label>
                    <select name="role" id="roles" class="form-control" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $roles)
                            <option value="{{ $roles->name }}">{{ $roles->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2">
                    <button class="btn btn-primary w-100" type="submit">Add User</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->
<script src="assets/js/pages/password-addon.init.js"></script>
@endsection