
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
            @php $button = auth()->user()->hasPermissions('roleadd')?'Add Role':''; @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="0" target="addrole" currentmonth=""/>
            
            <!-- start page title -->
            {{-- <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }} </h4>
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="row pb-2">
                    <div class="col-sm-6">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addrole"><i class="las la-plus me-1"></i> Add Role</button>
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
                                            <th scope="col">Role ID</th>
                                            <th scope="col">Role Name</th>
                                            <th scope="col" class="w-50">Role Permissions</th>
                                            @if(auth()->user()->role==1)
                                            <th scope="col">Set Permissions</th>                                            
                                            <th scope="col">Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody>@php $i=1; @endphp
                                        @if(count($roles)>0)
                                        @foreach($roles as $role)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ids[]" id="check1" value="{{ $role->id }}">
                                                </div>
                                            </td>
                                            <td><p class="fw-medium mb-0">{{ $role->id }}</p></td>
                                            <td>{{ $role->name }}</td>
                                            <td style="word-wrap: break-word;white-space:normal;">
                                                @foreach ($role->permissions as $permission)
                                                    <span class="badge text-bg-secondary">{{ $permission->name }}</span>
                                                @endforeach                                                
                                            </td>
                                            @if(auth()->user()->role==1)
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="{{ route('rolegetpermissions',[$role->id]) }}">
                                                    <i class="las la-user-shield fs-18 align-middle"></i> Permissions
                                                </a>
                                            </td>                                            
                                            <td>
                                                <a class="btn btn-primary btn-sm get-data" href="javascript::void(0)" data-id="{{ $role->id }}" data-value="{{ $role->name }}" data-bs-toggle="modal" data-bs-target="#editrole">
                                                    <i class="las la-pen-alt fs-18 align-middle"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm" href="{{ route('roledelete',[$role->id]) }}">
                                                    <i class="las la-trash-alt fs-18 align-middle"></i> Delete
                                                </a>
                                            </td>
                                            @endif
                                            {{-- <td>$240.00</td> --}}
                                            
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                </div>
            </div>

            @if(count($roles)>0)
                {{ $roles->links() }}
            @endif
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
<!-- end main content-->

<!-- Modal -->
<div class="modal fade" id="addrole" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Role</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('roleadd') }}" method="post">
        <div class="modal-body">
            @csrf
            <div class="mb-3">
                <label for="role" class="form-label">Role Name</label>
                <input type="text" name="role_name" class="form-control" id="role" placeholder="Enter Role Name" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Role</button>
        </div>
        </form>
      </div>
    </div>
  </div>
<div class="modal fade" id="editrole" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('roleupdate') }}" method="post">
        <div class="modal-body">
            @csrf
            <div class="mb-3">
                <label for="role" class="form-label">Role Name</label>
                <input type="text" name="name" class="form-control" id="role_name" placeholder="Enter Role Name">
                <input type="hidden" name="role_id" class="form-control" id="role_id">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Role</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    $('.get-data').click(function(event) {
      event.preventDefault();
      const value = $(this).data('value');
      const id = $(this).data('id');
      $('#role_name').val(value);
      $('#role_id').val(id);
    });
  </script>


@endsection