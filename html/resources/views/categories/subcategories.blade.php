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
            <x-bar-after-top-bar title="{{ $title }}" button="Add SubCategory" count="{{count($subcategorylist)>0?$subcategorylist->total():0}}" target="addcategory" currentmonth=""/>
            <!-- start page title -->
            {{-- <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ Invoice }}</a></li>
                                <li class="breadcrumb-item active">New Invoice</li>
                            </ol>
                        </div>
                        <h4 class="mb-sm-0">{{ $title }} ({{count($subcategorylist)>0?$subcategorylist->total():0}})</h4>
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center float-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addcategory"><i class="las la-plus me-1"></i> Add SubCategory</button>
                    </div>
                </div>
                @endif
            </div> --}}
            <!-- end page title -->

            {{-- request()->route('id')==0 and $cat->parent_id==0 for pervent future categories --}}
            
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
                                            @if(request()->route('id')==0)
                                            <th scope="col">SubCategory</th>
                                            @endif
                                            <th scope="col">Asset/Liability</th>
                                            <th scope="col">Subscription</th>
                                            <th scope="col">Date</th>
                                            @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody>@php $i=1; @endphp
                                        @forelse($subcategorylist as $cat)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ids[]" id="check1" value="{{ $cat->id }}">
                                                </div>
                                            </td>
                                            <td><p class="fw-medium mb-0">{{ $cat->id }}</p></td>
                                            <td>{{ $cat->name }}</td>
                                            @if($cat->parent_id==0)
                                            <td>                                                
                                                <a class="btn btn-primary btn-sm" href="{{ route('subcategorylist',[$cat->parent_id,$cat->id]) }}">
                                                    View
                                                </a>                                                
                                            </td>
                                            @endif
                                            <td>{{ $cat->type }}</td>
                                            <td>{{ $cat->subscription_amount??'-' }}</td>
                                            <td>{{ $cat->created_at }}</td>
                                            {{-- <td>$240.00</td> --}}
                                            @if(auth()->user()->role==1)
                                            <td>
                                                <a class="btn btn-primary btn-sm edit-item-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editcategory" id="{{ $cat->id }}" catname="{{ $cat->name }}" cattype="{{ $cat->type }}" subamt="{{ $cat->subscription_amount }}">
                                                    <i class="las la-pen-alt fs-18 align-middle"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm remove-item-btn" href="{{ route('subcategorydelete',[$cat->id,$cat->name,$pid,$id]) }}">
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

                @if(count($subcategorylist)>0)
                {{ $subcategorylist->links() }}
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add SubCategory</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('subcategoryadd')}}" class="auth-input" method="post">
                @csrf
                <input type="hidden" name="sub_pat_id" value="{{ $pid }}">
                <input type="hidden" name="sub_cat_id" value="{{ $id }}">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter SubCategory Name" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Assets/Liabilities</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">Select Option</option>
                        <option value="Assets">Assets</option>
                        <option value="Liabilities">Liabilities</option>
                    </select>
                </div>
                <div class="mb-3" id='subscription' style="display:none">
                    <label for="subscription_amount" class="form-label">Enter Subscription Amount (Only If applicable)</label>
                    <input type="number" step="0.01" name="subscription_amount" class="form-control" id="subscription_amount" placeholder="Enter Subscription Amount">
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" type="submit">Add SubCategory</button>
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit SubCategory</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('subcategoryupdate')}}" class="auth-input" method="post">
                @csrf
                <input type="hidden" name="sub_pat_id" id="sub_pat_id" value="{{ $pid }}">
                <input type="hidden" name="sub_cat_id" id="sub_cat_id" value="{{ $id }}">
                <input type="hidden" name="cat_id" id="cat_id">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="catname" class="form-control" id="catname" placeholder="Enter SubCategory Name" required>
                </div>
                <div class="mb-3">
                    <label for="cattype" class="form-label">Assets/Liabilities</label>
                    <select name="cattype" id="cattype" class="form-control" required>
                        <option value="">Select Option</option>
                        <option value="Assets">Assets</option>
                        <option value="Liabilities">Liabilities</option>
                    </select>
                </div>
                <div class="mb-3" id='edit_subscription' style="display:none">
                    <label for="catsubamt" class="form-label">Enter Subscription Amount (Only If applicable)</label>
                    <input type="number" step="0.01" name="catsubamt" class="form-control" id="catsubamt" placeholder="Enter Subscription Amount">
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" type="submit">Update SubCategory</button>
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
        $('#cattype').val($(this).attr('cattype'));
        $('#catsubamt').val($(this).attr('subamt'));
        if($(this).attr('cattype')=='Assets'){
            $('#edit_subscription').hide()
        }else{
            $('#edit_subscription').show()
        }
    })
    
    //For Add and Edit on change category type subscription
    $('#type').on('change',function(){
        selected_value = $(this).val();
        if(selected_value=='Liabilities'){
            $('#subscription').show()
        }else{
            $('#subscription').hide()
        }
    })
    $('#cattype').on('change',function(){
        selected_value = $(this).val();
        if(selected_value=='Liabilities'){
            $('#edit_subscription').show()
        }else{
            $('#edit_subscription').hide()
        }
    })
  </script>
<!-- end main content-->
<script src="assets/js/pages/password-addon.init.js"></script>
@endsection