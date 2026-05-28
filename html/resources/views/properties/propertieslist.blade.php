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
                $button = auth()->user()->hasPermissions('useradd')?'Add Properties':''; 
                $start = request('start');
                $end = request('end');
                $cat_id = request('cat_id');
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="0" target="addproperty" currentmonth=""/>            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">Nomenclature of items</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Total Cost</th>
                                            <th scope="col">Remarks</th>
                                            <th scope="col">File</th>
                                        @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                        @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse($propertieslist as $property)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$property->pro_item}}</td>
                                                <td>{{$property->pro_date}}</td>
                                                <td>{{$property->pro_qty}}</td>
                                                <td>{{$property->pro_total}}</td>
                                                <td>{{$property->pro_remarks??'-'}}</td>
                                                <td>
                                                    <a 
                                                    @if($property->pro_file!='')href="{{ asset('storage/'.$property->pro_file) }}" target="_blank">File Attached</a>
                                                    @else
                                                    <a href="javascript::void(0)">No File Attached</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript::void(0)" class="btn btn-primary btn-sm edit-button" id="{{ $property->id }}" cat_id="{{ $property->category_id }}" image_path="{{$property->cheque_file}}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    <a href="{{ route('propertiesdelete',[$property->id]) }}" class="btn btn-danger btn-sm" id="{{ $property->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
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
<div class="modal fade" id="addproperty" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Property</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('propertiesadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                <input type="hidden" name="start" value="{{$start}}">
                <input type="hidden" name="end" value="{{$end}}">
                @csrf
                <div class="mb-3">
                    <label for="pro_item" class="form-label">Item</label>
                    <input type="text" name="pro_item" class="form-control" id="pro_item" placeholder="Enter Item Name" required>
                </div>
                <div class="mb-3">
                    <label for="pro_date" class="form-label">Date</label>
                    <input type="date" value="{{ $start }}" name="pro_date" class="form-control" id="pro_date" placeholder="Date" required>
                </div>
                <div class="mb-3">
                    <label for="pro_qty" class="form-label">Qty</label>
                    <input type="number" name="pro_qty" step="0.01" class="form-control" id="pro_qty" placeholder="Enter Qty" required>
                </div>

                <div class="mb-3">
                    <label for="pro_total" class="form-label">Total Cost</label>
                    <input type="number" name="pro_total" step="0.01" class="form-control" id="pro_total" placeholder="Enter Total Amount" required>
                </div>
                <div class="mb-3">
                    <label for="pro_remarks" class="form-label">Remarks</label>
                    <input type="text" name="pro_remarks" class="form-control" id="pro_remarks" placeholder="Enter Remarks">
                </div>                
                <div class="mb-3">
                    <label for="pro_file" class="form-label">File</label>
                    <input type="file" name="pro_file" class="form-control" id="pro_file" placeholder="Cheque File">
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit">Add Property</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->

<div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPropertyModalLabel">Edit Property</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPropertyForm" action="{{ route('propertiesedit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="pro_id" id="pro_id">
                    <input type="hidden" name="category_id" id="pro_cat_id">
                    
                    <div class="mb-3">
                        <label for="edit_pro_item" class="form-label">Item</label>
                        <input type="text" name="pro_item" class="form-control" id="edit_pro_item" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pro_date" class="form-label">Date</label>
                        <input type="date" value="{{ $start }}" name="pro_date" class="form-control" id="edit_pro_date" placeholder="Date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pro_qty" class="form-label">Qty</label>
                        <input type="number" step="0.01" name="pro_qty" class="form-control" id="edit_pro_qty" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_pro_total" class="form-label">Total Cost</label>
                        <input type="number" step="0.01" name="pro_total" class="form-control" id="edit_pro_total" required>
                    </div>                                        
                    
                    <div class="mb-3">
                        <label for="edit_pro_remarks" class="form-label">Remarks</label>
                        <input type="text" name="pro_remarks" class="form-control" id="edit_pro_remarks" placeholder="Enter Remarks">
                    </div>  

                    <div class="mb-3">
                        <label for="edit_pro_file" class="form-label">File</label>
                        <input type="file" name="pro_file" class="form-control" id="edit_pro_file" />
                        <input type="hidden" name="pro_file_path" class="form-control" id="edit_pro_file_path" />
                        <small>Current file: <span id="current_pro_file"></span></small>
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
        
        var currentRow = $(this).closest('tr'); // Find the row
        var proId = $(this).attr('id'); // Get pro ID
        var proCatId = $(this).attr('cat_id'); // Get Category ID
        var proImagePath = $(this).attr('image_path'); // Get Category ID
        var proItem = currentRow.find('td:eq(1)').text();
        var proDate = currentRow.find('td:eq(2)').text();
        var proQty = currentRow.find('td:eq(3)').text();
        var proTotal = currentRow.find('td:eq(4)').text();
        var proRemarks = currentRow.find('td:eq(5)').text();
        var proFile = currentRow.find('td:eq(6) a').attr('href'); // URL of the file
        
        // Populate modal fields
        $('#pro_id').val(proId);
        $('#pro_cat_id').val(proCatId);
        $('#edit_pro_item').val(proItem);
        $('#edit_pro_date').val(proDate);
        $('#edit_pro_qty').val(proQty);
        $('#edit_pro_total').val(proTotal);
        $('#edit_pro_remarks').val(proRemarks);
        // $('#current_pro_file').text(proFile!='javascript::void(0)' ? proFile : "No file uploaded");
        $('#edit_pro_file_path').val(proImagePath!=''?proImagePath:"");
        if(proFile!='javascript::void(0)'){
            $('#current_pro_file').html(`<img src=${proFile} width="50" height="50" />`)
        }else{
            $('#current_pro_file').text("No file uploaded");
        }
        
        // Show the modal
        $('#editPropertyModal').modal('show');
    });
});

</script>
@endsection