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
                $button = auth()->user()->hasPermissions('categoryadd')?'Add Category':''; 
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{count($categorylist)>0?$categorylist->total():0}}" target="addcategory" currentmonth=""/>
            <!-- start page title -->
            {{-- <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }} ({{count($categorylist)>0?$categorylist->total():0}})</h4>
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center float-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addcategory"><i class="las la-plus me-1"></i> Add Category</button>
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
                                            <th scope="col">Category ID</th>
                                            <th scope="col">Category Name</th>
                                            <th scope="col">Total Allotment</th>
                                            <th scope="col">SubCategory</th>
                                            <th scope="col">Date</th>
                                            @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody>@php $i=1; @endphp
                                        @forelse($categorylist as $cat)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ids[]" id="check1" value="{{ $cat->id }}">
                                                </div>
                                            </td>
                                            <td><p class="fw-medium mb-0">{{ $cat->id }}</p></td>
                                            <td>{{ $cat->name }}</td>
                                            <td>
                                                @if($cat->has_total_allotment)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="{{ route('subcategorylist',[$cat->parent_id,$cat->id]) }}">
                                                    View
                                                </a>
                                            </td>
                                            <td>{{ $cat->created_at }}</td>
                                            {{-- <td>$240.00</td> --}}
                                            @if(auth()->user()->role==1)
                                            <td>
                                                <a class="btn btn-primary btn-sm edit-item-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editcategory" id="{{ $cat->id }}" catname="{{ $cat->name }}" has-allotment="{{ $cat->has_total_allotment }}">
                                                    <i class="las la-pen-alt fs-18 align-middle"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm remove-item-btn" href="{{ route('categorydelete',[$cat->id,$cat->name]) }}">
                                                    <i class="las la-trash-alt fs-18 align-middle"></i> Delete
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center">No Category found</td></tr>
                                            @endforelse
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                </div>
            </div>

                @if(count($categorylist)>0)
                {{ $categorylist->links() }}
                @endif
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<!-- Modal -->
<div class="modal fade" id="addcategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('categoryadd')}}" class="auth-input" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Category Name">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="has_total_allotment" id="has_total_allotment" value="1">
                        <label class="form-check-label" for="has_total_allotment">
                            This category has Total Allotment option
                        </label>
                    </div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-primary w-100" type="submit">Add Category</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editcategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('categoryupdate')}}" class="auth-input" method="post">
                @csrf
                <input type="hidden" name="cat_id" id="cat_id">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="catname" class="form-control" id="catname" placeholder="Enter Category Name">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="has_total_allotment" id="edit_has_total_allotment" value="1">
                        <label class="form-check-label" for="edit_has_total_allotment">
                            This category has Total Allotment option
                        </label>
                    </div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-primary w-100" type="submit">Update Category</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    $('.edit-item-btn').on("click",function(){
        $('#cat_id').val($(this).attr('id'));
        $('#catname').val($(this).attr('catname'));
        
        // Set total allotment checkbox
        if($(this).attr('has-allotment') == '1') {
            $('#edit_has_total_allotment').prop('checked', true);
        } else {
            $('#edit_has_total_allotment').prop('checked', false);
        }
    })
  </script>

<!-- end main content-->
<script src="assets/js/pages/password-addon.init.js"></script>
@endsection