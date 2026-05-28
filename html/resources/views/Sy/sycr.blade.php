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
                $button = auth()->user()->hasPermissions('useradd')?'Add SyCr':''; 
                $start = request('start');
                $end = request('end');
                $cat_id = request('cat_id');
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="0" target="addsycr" currentmonth=""/>            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">Label</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Date</th>
                                        @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                        @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse($sycrlist as $list)
                                            <tr>
                                                <td>{{$i++}}</td> 
                                                <td>{{$list->sycr_label}}</td>
                                                <td>{{$list->sycr_amount}}</td>
                                                <td>{{$list->sycr_date}}</td>
                                                <td>
                                                    <a href="javascript::void(0)" class="btn btn-primary btn-sm edit-button" id="{{ $list->id }}" cat_id="{{ $list->category_id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    <a href="{{ route('sycrdelete',[$list->id]) }}" class="btn btn-danger btn-sm" id="{{ $list->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
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
<div class="modal fade" id="addsycr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add SyCr</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('sycradd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                <input type="hidden" name="start" value="{{$start}}">
                <input type="hidden" name="end" value="{{$end}}">
                @csrf
                <div id="entriesContainer">
                    <div class="entry">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control w-50" placeholder="Enter Label" name="label[]" required>
                            <input type="number" class="form-control" step="0.01" placeholder="Enter Amount" name="amount[]" required>
                            <button type="button" class="btn btn-primary" id="addEntryButton">+</button>
                        </div>
                    </div>
                </div>   
                <div class="mb-3">
                    <input type="date" value="{{ $start }}" name="sycr_date" class="form-control" id="sycr_date" required>
                </div>            
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit">Add Sycr</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->

<script>
    $(document).ready(function() {
        // Add a new set of inputs for label and amount
        $("#addEntryButton").click(function() {
            var newEntry = `<div class="entry">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control w-50" placeholder="Enter Label" name="label[]" required>
                            <input type="number" class="form-control" step="0.01" placeholder="Enter Amount" name="amount[]" required>
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

<div class="modal fade" id="editSycrModal" tabindex="-1" aria-labelledby="editSycrModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSycrModalLabel">Edit SyCr</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSycrForm" action="{{ route('sycredit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="sycr_id" id="sycr_id">
                    <input type="hidden" name="category_id" id="sycr_cat_id">
                    
                    <div class="mb-3">
                        <label for="edit_sycr_label" class="form-label">Label</label>
                        <input type="text" name="label" class="form-control" id="edit_sycr_label" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_sycr_amount" class="form-label">Amount</label>
                        <input type="number" name="amount" step="0.01" class="form-control" id="edit_sycr_amount" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_sycr_date" class="form-label">Date</label>
                        <input type="date" name="sycr_date" step="0.01" class="form-control" id="edit_sycr_date" required>
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
        var sycrId = $(this).attr('id');
        var sycrCatId = $(this).attr('cat_id');
        var sycrNumber = currentRow.find('td:eq(1)').text();
        var sycrDate = currentRow.find('td:eq(2)').text();
        var sycrAmount = currentRow.find('td:eq(3)').text();
        
        // Populate modal fields
        $('#sycr_id').val(sycrId);
        $('#sycr_cat_id').val(sycrCatId);
        $('#edit_sycr_label').val(sycrNumber);
        $('#edit_sycr_amount').val(sycrDate);
        $('#edit_sycr_date').val(sycrAmount);
        
        // Show the modal
        $('#editSycrModal').modal('show');
    });
});

</script>
@endsection