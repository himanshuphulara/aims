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
                $button = auth()->user()->hasPermissions('useradd')?'Add Sub Category':''; 
                $start = request('start');
                $end = request('end');
                $cat_id = request('cat_id');
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{count($subcats)}}" target="addmessbillcategory" currentmonth=""/>            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">Main Category</th>
                                            <th scope="col">Sub category</th>
                                            <th scope="col">Amount</th>
                                        @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                        @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse($subcats as $list)
                                            <tr>
                                                <td>{{$i++}}</td> 
                                                <td>{{$list->main_category}}</td>
                                                <td>{{$list->subcategory_name}}</td>
                                                <td>{{$list->amount}}</td>
                                                <td>
                                                    <a href="javascript::void(0)" class="btn btn-primary btn-sm edit-button" id="{{ $list->id }}" cat_id="{{ $list->category_id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    {{-- <a href="{{ route('messbillsubcategorydelete',[$list->id]) }}" class="btn btn-danger btn-sm" id="{{ $list->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a> --}}
                                                </td>   
                                            </tr>
                                        @empty
                                            <tr><td colspan=6 class="text-center">No Data Found</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<!-- Modal -->
<div class="modal fade" id="addmessbillcategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Sub Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('messbilladdsubcategory')}}" class="auth-input" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                <input type="hidden" name="subcat_date" value="{{$start}}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <label for="cat_name" class="form-label">Select Category</label>
                        <select class="form-select mb-3" name="cat_name" id="cat_name" required>
                            <option value="">Select one Option</option>
                            @foreach ($subcategories as $cate)
                            @if(strtolower($cate->name) == 'mess fund' || strtolower($cate->name) == 'cat stock')
                                @php 
                                    $catname = strtolower(str_replace(" ", "_", $cate->name));  
                                @endphp
                                    <option value="{{$catname}}">{{$cate->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div id="entriesContainer" style="display: none">
                        <div class="entry">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control w-50" placeholder="Enter Sub Category Name" name="label[]">
                                <input type="number" step="0.01" class="form-control w-25" placeholder="Enter Amount" name="amount[]">
                                <button type="button" class="btn btn-primary" id="addEntryButton">+</button>
                            </div>
                        </div>
                    </div>   
                </div>                  
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit">Save Detail</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    $('#cat_name').on('change',function(){
        $('#entriesContainer').show();
    });
    $(document).ready(function() {
        // Add a new set of inputs for label and amount
        $("#addEntryButton").click(function() {
            var newEntry = `<div class="entry">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control w-50" placeholder="Enter Sub Category Name" name="label[]">
                            <input type="number" step="0.01" class="form-control w-25" placeholder="Enter Amount" name="amount[]">
                            <button type="button" class="btn btn-danger deleteEntryButton">-</button>
                        </div>
                    </div>`;
            $("#entriesContainer").append(newEntry); // Append the new fields
        });
        $(document).on("click", ".deleteEntryButton", function() {
            $(this).closest(".entry").remove();  // Remove the specific entry div
        });
    });
  </script>

<div class="modal fade" id="editSubCategory" tabindex="-1" aria-labelledby="editSubCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubCategoryLabel">Edit Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSydrForm" action="{{ route('messbillsubcategoryedit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="sub_id">
                    <input type="hidden" name="category_id" id="sub_cat_id">
                    <input type="hidden" name="main_category" id="edit_sub_main">
                    
                    <div class="mb-3">
                        <label for="edit_sub_cat" class="form-label">Category</label>
                        <input type="text" name="subcategory_name" class="form-control" id="edit_sub_cat" required>
                        <label for="edit_amount" class="form-label">Amount</label>
                        <input type="text" name="amount" class="form-control" id="edit_amount">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 text-uppercase">Update Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    $('.edit-button').on('click', function(event) {
        event.preventDefault();
        
        var currentRow = $(this).closest('tr');
        var subId = $(this).attr('id');
        var subCatId = $(this).attr('cat_id');
        var subMain = currentRow.find('td:eq(1)').text();
        var subCat = currentRow.find('td:eq(2)').text();
        var amt = currentRow.find('td:eq(3)').text();
        
        // Populate modal fields
        $('#sub_id').val(subId);
        $('#sub_cat_id').val(subCatId);
        $('#edit_sub_main').val(subMain);
        $('#edit_sub_cat').val(subCat);
        $('#edit_amount').val(amt);
        
        // Show the modal
        $('#editSubCategory').modal('show');
    });
});

</script>
@endsection
