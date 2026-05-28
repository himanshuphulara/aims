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
                $button = auth()->user()->hasPermissions('useradd')?'Add Statement':''; 
                $start = request('start');
                $end = request('end');
                $cat_id = request('cat_id');
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="0" target="stmtModal" currentmonth=""/>            

            {{-- Start For Bank Statement --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">Amount As Per Bank Statement</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">File</th>
                                        @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                        @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse($bankstmt as $stmt)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td class="text-wrap">{{$stmt->stmt_label}}</td>
                                                <td>{{$stmt->stmt_amount}}</td>
                                                <td>{{$stmt->stmt_date}}</td>
                                                <td>
                                                    <a 
                                                    @if($stmt->stmt_file!='')href="{{ asset('storage/'.$stmt->stmt_file) }}" target="_blank">File Attached</a>
                                                    @else
                                                    <a href="javascript::void(0)">No File Attached</a>
                                                    @endif
                                                    
                                                </td>
                                                <td>
                                                    <a href="javascript::void(0)" class="btn btn-primary btn-sm edit-stmt-button" id="{{ $stmt->id }}" cat_id="{{ $stmt->category_id }}" image_path="{{$stmt->stmt_file}}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    <a href="{{ route('stmtdelete',[$stmt->id]) }}" class="btn btn-danger btn-sm" id="{{ $stmt->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
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
            {{-- End For Statement Detail --}}
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<!-- Modal -->
{{-- Start For Bank Statement --}}
<div class="modal fade" id="stmtModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Statement</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('stmtadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                <input type="hidden" name="start" value="{{$start}}">
                <input type="hidden" name="end" value="{{$end}}">
                @csrf
                <div class="mb-3">
                    <label for="stmt_label" class="form-label">Label</label>
                    <input type="type" name="stmt_label" class="form-control" id="stmt_label" placeholder="Enter Label" required>
                </div>
                <div class="mb-3">
                    <label for="stmt_amount" class="form-label">Amount</label>
                    <input type="number" name="stmt_amount" step="0.01" class="form-control" id="stmt_amount" placeholder="Enter Amount" required>
                </div>

                <div class="mb-3">
                    <label for="stmt_date" class="form-label">Date</label>
                    <input type="date" value="{{ $start }}" name="stmt_date" class="form-control" id="stmt_date" placeholder="Bank Date" required>
                </div>

                <div class="mb-3">
                    <label for="stmt_file" class="form-label">Attach File</label>
                    <input type="file" name="stmt_file" class="form-control" id="stmt_file" placeholder="Bank File">
                </div>                             
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit">Add Statement</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editStmtModal" tabindex="-1" aria-labelledby="editStmtModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStmtModalLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editstmtForm" action="{{ route('stmtedit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="stmt_id" id="stmt_id">
                    <input type="hidden" name="category_id" id="stmt_cat_id">
                    <div class="mb-3">
                        <label for="edit_stmt_label" class="form-label">Label</label>
                        <input type="test" name="stmt_label" class="form-control" id="edit_stmt_label" placeholder="Enter Label" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stmt_amount" class="form-label">Amount</label>
                        <input type="number" name="stmt_amount" class="form-control" id="edit_stmt_amount" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_stmt_date" class="form-label">Date</label>
                        <input type="date" name="stmt_date" class="form-control" id="edit_stmt_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_stmt_file" class="form-label">File</label>
                        <input type="file" name="stmt_file" class="form-control" id="edit_stmt_file" />
                        <input type="hidden" name="stmt_file_path" class="form-control" id="edit_stmt_file_path" />
                        <small>Current file: <span id="current_stmt_file"></span></small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 text-uppercase">Update Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End For Bank Statement --}}
<script>
    $(document).ready(function() {
    //For Bank Statement
    $('.edit-stmt-button').on('click', function(event) {
        event.preventDefault();
        
        var currentRow = $(this).closest('tr'); // Find the row
        var stmtId = $(this).attr('id'); // Get stmt ID
        var stmtCatId = $(this).attr('cat_id'); // Get Category ID
        var stmtImagePath = $(this).attr('image_path'); // Get Category ID
        var stmtLabel = currentRow.find('td:eq(1)').text();
        var stmtAmount = currentRow.find('td:eq(2)').text();
        var stmtDate = currentRow.find('td:eq(3)').text();
        var stmtFile = currentRow.find('td:eq(4) a').attr('href'); // URL of the file
        
        // Populate modal fields
        $('#stmt_id').val(stmtId);
        $('#stmt_cat_id').val(stmtCatId);
        $('#edit_stmt_label').val(stmtLabel);
        $('#edit_stmt_amount').val(stmtAmount);
        $('#edit_stmt_date').val(stmtDate);
        // $('#current_stmt_file').text(stmtFile!='javascript::void(0)' ? stmtFile : "No file uploaded");
        $('#edit_stmt_file_path').val(stmtImagePath!=''?stmtImagePath:"");
        if(stmtFile!='javascript::void(0)'){
            $('#current_stmt_file').html(`<img src=${stmtFile} width="50" height="50" />`)
        }else{
            $('#current_stmt_file').text("No file uploaded");
        }
        
        // Show the modal
        $('#editStmtModal').modal('show');
    });
});

</script>
@endsection