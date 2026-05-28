
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

            <!-- start page title -->
            <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }} </h4>
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="row pb-2">
                    <div class="col-sm-6">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addrole"><i class="las la-plus me-1"></i> Add Permission</button>
                    </div>
                </div>
                @endif
            </div>
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
                                            <th scope="col">Permission ID</th>
                                            <th scope="col">Permission Name</th>
                                            {{-- <th scope="col" style="width: 16%;">Status</th> --}}
                                            @if(auth()->user()->role==1)
                                            <th scope="col">Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody>@php $i=1; @endphp
                                        @if(count($permissions)>0)
                                        @foreach($permissions as $permission)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ids[]" id="check1" value="{{ $permission->id }}">
                                                </div>
                                            </td>
                                            <td><p class="fw-medium mb-0">{{ $permission->id }}</p></td>
                                            <td>{{ $permission->name }}</td>
                                            @if(auth()->user()->role==1)
                                            <td>
                                                <a class="btn btn-success get-data" href="javascript::void(0)" data-id="{{ $permission->id }}" data-value="{{ $permission->name }}" data-bs-toggle="modal" data-bs-target="#editrole">
                                                    <i class="las la-pen-alt fs-18 align-middle"></i>
                                                    
                                                </a>
                                                <a class="btn btn-danger" href="{{ route('delete',[$permission->id]) }}">
                                                    <i class="las la-trash-alt fs-18 align-middle"></i>
                                                    
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

            @if(count($permissions)>0)
                {{ $permissions->links() }}
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add permission</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('store') }}" method="post">
        <div class="modal-body">
            @csrf
            <div class="mb-3">
                <label for="permission" class="form-label">Permission Name</label>
                <input type="text" name="role_name" class="form-control" id="permission" placeholder="Enter Permission Name">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Permission</button>
        </div>
        </form>
      </div>
    </div>
  </div>
<div class="modal fade" id="editrole" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Permission</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('update') }}" method="post">
        <div class="modal-body">
            @csrf
            <div class="mb-3">
                <label for="permission" class="form-label">Permission Name</label>
                <input type="text" name="name" class="form-control" id="role_name" placeholder="Enter Permission Name">
                <input type="hidden" name="role_id" class="form-control" id="role_id">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Permission</button>
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