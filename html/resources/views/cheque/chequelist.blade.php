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
                $button = auth()->user()->hasPermissions('useradd')?'Add Cheque':''; 
                $start = request('start');
                $end = request('end');
                $cat_id = request('cat_id');
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="0" target="addcheque" currentmonth=""/>            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">Cheque Detail</th>
                                            <th scope="col">Cheque Number</th>
                                            <th scope="col">Cheque Date</th>
                                            <th scope="col">Cheque Amount</th>
                                            <th scope="col">Cheque File</th>
                                            <th scope="col">Status</th>
                                        @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                        @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse($chequelist as $cheque)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$cheque->cheque_detail}}</td>
                                                <td>{{$cheque->cheque_number}}</td>
                                                <td>{{$cheque->cheque_date}}</td>
                                                <td>{{$cheque->cheque_amount}}</td>
                                                <td>
                                                    <a 
                                                    @if($cheque->cheque_file!='')href="{{ asset('storage/'.$cheque->cheque_file) }}" target="_blank"
                                                    @else
                                                    href="javascript::void(0)"
                                                    @endif
                                                    >{{ $cheque->cheque_number }}</a>
                                                </td>
                                                <td>
                                                    @if($cheque->cheque_status==1)
                                                    <span class="badge bg-primary">Cleared</span>
                                                    @else
                                                    <span class="badge bg-danger">UnCleared</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript::void(0)" class="btn btn-primary btn-sm edit-button" id="{{ $cheque->id }}" cat_id="{{ $cheque->category_id }}" image_path="{{$cheque->cheque_file}}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    <a href="{{ route('chequedelete',[$cheque->id]) }}" class="btn btn-danger btn-sm" id="{{ $cheque->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
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
<div class="modal fade" id="addcheque" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Cheque</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('chequeadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                <input type="hidden" name="start" value="{{$start}}">
                <input type="hidden" name="end" value="{{$end}}">
                @csrf
                <div class="mb-3">
                    <label for="cheque_detail" class="form-label">Cheque Detail</label>
                    <input type="text" name="cheque_detail" class="form-control" id="cheque_detail" placeholder="Enter Cheque Detail" required>
                </div>
                <div class="mb-3">
                    <label for="cheque_number" class="form-label">Cheque Number</label>
                    <input type="text" name="cheque_number" class="form-control" id="cheque_number" placeholder="Enter Cheque Number" required>
                </div>
                <div class="mb-3">
                    <label for="cheque_date" class="form-label">Cheque Date</label>
                    <input type="date" value="{{ $start }}" name="cheque_date" class="form-control" id="cheque_date" placeholder="Cheque Date" required>
                </div>

                <div class="mb-3">
                    <label for="cheque_amount" class="form-label">Cheque Amount</label>
                    <input type="number" name="cheque_amount" step="0.01" class="form-control" id="cheque_amount" placeholder="Enter Cheque Amount" required>
                </div>
                <div class="mb-3">
                    <label for="cheque_file" class="form-label">Cheque File</label>
                    <input type="file" name="cheque_file" class="form-control" id="cheque_file" placeholder="Cheque File">
                </div>                
                <div class="mb-3">
                    <label for="cheque_status" class="form-label">Cheque Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cheque_status" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Cleared</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="cheque_status" id="inlineRadio2" value="0">
                        <label class="form-check-label" for="inlineRadio2">UnCleared</label>
                      </div>
                </div>                
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit">Add Cheque</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->

<div class="modal fade" id="editChequeModal" tabindex="-1" aria-labelledby="editChequeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChequeModalLabel">Edit Cheque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editChequeForm" action="{{ route('chequeedit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="cheque_id" id="cheque_id">
                    <input type="hidden" name="category_id" id="cheque_cat_id">
                    
                    <div class="mb-3">
                        <label for="edit_cheque_detail" class="form-label">Cheque Detail</label>
                        <input type="text" name="cheque_detail" class="form-control" id="edit_cheque_detail" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_cheque_number" class="form-label">Cheque Number</label>
                        <input type="text" name="cheque_number" class="form-control" id="edit_cheque_number" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_cheque_date" class="form-label">Cheque Date</label>
                        <input type="date" name="cheque_date" class="form-control" id="edit_cheque_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_cheque_amount" class="form-label">Cheque Amount</label>
                        <input type="number" name="cheque_amount" class="form-control" id="edit_cheque_amount" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_cheque_file" class="form-label">Cheque File</label>
                        <input type="file" name="cheque_file" class="form-control" id="edit_cheque_file" />
                        <input type="hidden" name="cheque_file_path" class="form-control" id="edit_cheque_file_path" />
                        <small>Current file: <span id="current_cheque_file"></span></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cheque Status</label><br>
                        <input type="radio" name="cheque_status" id="edit_status_cleared" value="1"> Cleared
                        <input type="radio" name="cheque_status" id="edit_status_uncleared" value="0"> UnCleared
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
        var chequeId = $(this).attr('id'); // Get cheque ID
        var chequeCatId = $(this).attr('cat_id'); // Get Category ID
        var chequeImagePath = $(this).attr('image_path'); // Get Category ID
        var chequeDetail = currentRow.find('td:eq(1)').text();
        var chequeNumber = currentRow.find('td:eq(2)').text();
        var chequeDate = currentRow.find('td:eq(3)').text();
        var chequeAmount = currentRow.find('td:eq(4)').text();
        var chequeFile = currentRow.find('td:eq(5) a').attr('href'); // URL of the file
        var chequeStatus = currentRow.find('td:eq(6) .badge').text() === 'Cleared' ? '1' : '0';
        
        // Populate modal fields
        $('#cheque_id').val(chequeId);
        $('#cheque_cat_id').val(chequeCatId);
        $('#edit_cheque_detail').val(chequeDetail);
        $('#edit_cheque_number').val(chequeNumber);
        $('#edit_cheque_date').val(chequeDate);
        $('#edit_cheque_amount').val(chequeAmount);
        // $('#current_cheque_file').text(chequeFile!='javascript::void(0)' ? chequeFile : "No file uploaded");
        $('#edit_cheque_file_path').val(chequeImagePath!=''?chequeImagePath:"");
        if(chequeFile!='javascript::void(0)'){
            $('#current_cheque_file').html(`<img src=${chequeFile} width="50" height="50" />`)
        }else{
            $('#current_cheque_file').text("No file uploaded");
        }
        
        if (chequeStatus === '1') {
            $('#edit_status_cleared').prop('checked', true);
        } else {
            $('#edit_status_uncleared').prop('checked', true);
        }
        
        // Show the modal
        $('#editChequeModal').modal('show');
    });
});

</script>
@endsection