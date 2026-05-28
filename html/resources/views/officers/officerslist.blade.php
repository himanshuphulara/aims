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
                $button = auth()->user()->hasPermissions('useradd')?'Add Officer':''; 
                $start = request('start');
                $end = request('end');
                $cat_id = request('cat_id');
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{count($officerlist)}}" target="addofficer" currentmonth=""/>            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">Rank</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">TOS</th>
                                            <th scope="col">SOS</th>
                                            <th scope="col">Marital Status</th>
                                            <th scope="col" style="width: 25%;">Subscriptions</th>
                                        @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 12%;">Action</th>
                                        @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse($officerlist as $list)
                                            <tr>
                                                <td>{{$i++}}</td> 
                                                <td>{{$list->officer_rank}}</td>
                                                <td>{{$list->officer_name}}</td>
                                                <td>{{$list->officer_tos}}</td>
                                                <td>{{$list->officer_sos}}</td>
                                                <td>{{$list->marital_status}}</td>
                                                <td class="text-wrap">
                                                    @foreach($list->subscriptions_json as $key=>$value)
                                                        {{ $key }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a href="javascript::void(0)" class="btn btn-primary btn-sm edit-button" id="{{ $list->id }}" cat_id="{{ $list->category_id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    <a href="{{ route('officerdelete',[$list->id]) }}" class="btn btn-danger btn-sm" id="{{ $list->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
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
<div class="modal fade" id="addofficer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Officer Detail</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('officeradd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                @csrf
                <div class="mb-3">
                    <label for="officer_rank" class="form-label">Officer Rank</label>
                    <input type="text" name="officer_rank" class="form-control" id="officer_rank" placeholder="Enter Officer Rank">
                </div>
                <div class="mb-3">
                    <label for="officer_name" class="form-label">Officer Name</label>
                    <input type="text" name="officer_name" class="form-control" id="officer_name" placeholder="Enter Officer Name" required>
                </div>
                <div class="mb-3">
                    <label for="officer_tos" class="form-label">TOS Date</label>
                    <input type="date" value="{{ $start }}" name="officer_tos" class="form-control" id="officer_tos" placeholder="Cheque Date">
                </div>

                <div class="mb-3">
                    <label for="officer_sos" class="form-label">SOS Date</label>
                    <input type="date" name="officer_sos" step="0.01" class="form-control" id="officer_sos" placeholder="Enter Cheque Amount">
                </div>
                <div class="mb-3">
                    <label for="marital_status" class="form-label">Marital  Status: </label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marital_status" id="inlineRadio1" value="Married">
                        <label class="form-check-label" for="inlineRadio1">Married</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marital_status" id="inlineRadio2" value="Unmarried">
                        <label class="form-check-label" for="inlineRadio2">UnMarried</label>
                      </div>
                </div>   
                <div class="mb-3">
                    <label class="form-label">Subscriptions</label>
                    <input type="checkbox" id="checkAll"  class="form-check-input border-primary"> Check All<br>
                    @forelse ($subscriptions as $sub)
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input" name="subscriptions[{{ strtolower(str_replace(" ","_",$sub->name)) }}]" type="checkbox" id="{{ $sub->subscription_amount }}" value="{{ $sub->subscription_amount }}">
                        <label class="form-check-label" for="{{ $sub->subscription_amount }}">{{ $sub->name }}</label>
                    </div>                        
                    @empty                        
                    @endforelse
                </div>           
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit">Add Officer</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->

<div class="modal fade" id="editOfficerModal" tabindex="-1" aria-labelledby="editOfficerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOfficerModalLabel">Edit Officer Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editOfficerForm" action="{{ route('officeredit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="officer_id" id="officer_id">
                    <input type="hidden" name="category_id" id="officer_cat_id">
                    
                    <div class="mb-3">
                        <label for="edit_officer_rank" class="form-label">Officer Rank</label>
                        <input type="text" name="officer_rank" class="form-control" id="edit_officer_rank" placeholder="Enter Officer Rank">
                    </div>
                    <div class="mb-3">
                        <label for="edit_officer_name" class="form-label">Officer Name</label>
                        <input type="text" name="officer_name" class="form-control" id="edit_officer_name" placeholder="Enter Officer Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_officer_tos" class="form-label">TOS Date</label>
                        <input type="date" value="{{ $start }}" name="officer_tos" class="form-control" id="edit_officer_tos" placeholder="Cheque Date">
                    </div>
    
                    <div class="mb-3">
                        <label for="edit_officer_sos" class="form-label">SOS Date</label>
                        <input type="date" name="officer_sos" step="0.01" class="form-control" id="edit_officer_sos" placeholder="Enter Cheque Amount">
                    </div>
                    <div class="mb-3">
                        <label for="edit_marital_status" class="form-label">Marital  Status: </label> <br>
                        <input type="radio" name="marital_status" id="edit_status_married" value="Married"> Married
                        <input type="radio" name="marital_status" id="edit_status_unmarried" value="Unmarried"> UnMarried
                    </div>   
                    <div class="mb-3">
                        <label class="form-label">Subscriptions</label>
                        @forelse ($subscriptions as $sub)
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input dbvalues" name="subscriptions[{{ strtolower(str_replace(" ","_",$sub->name)) }}]" type="checkbox" id="{{ $sub->subscription_amount }}" value="{{ $sub->subscription_amount }}">
                            <label class="form-check-label" for="{{ $sub->subscription_amount }}">{{ $sub->name }}</label>
                        </div>                        
                        @empty                        
                        @endforelse
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
        var officerId = $(this).attr('id');
        var officerCatId = $(this).attr('cat_id');
        var officerRank = currentRow.find('td:eq(1)').text();
        var officerName = currentRow.find('td:eq(2)').text();
        var officerTOS = currentRow.find('td:eq(3)').text();
        var officerSOS = currentRow.find('td:eq(4)').text();
        var officerStatus= currentRow.find('td:eq(5)').text() === 'Married' ? '1' : '0';
        var officerSub= currentRow.find('td:eq(6)').text();
        
        // Populate modal fields
        $('#officer_id').val(officerId);
        $('#officer_cat_id').val(officerCatId);
        $('#edit_officer_rank').val(officerRank);
        $('#edit_officer_name').val(officerName);
        $('#edit_officer_tos').val(officerTOS);
        $('#edit_officer_sos').val(officerSOS);
        
        if (officerStatus === '1') {
            $('#edit_status_married').prop('checked', true);
        } else {
            $('#edit_status_unmarried').prop('checked', true);
        }
        
        var selectedSubscriptions = officerSub.split(' ').map(function(sub) {
            return sub.trim();
        });

        $('input[name^="subscriptions"]').each(function() {
            var checkboxName = $(this).attr('name');
            var checkboxValue = checkboxName.replace('subscriptions[', '').replace(']', '').trim(); 
            // Check the checkbox if its value is in the selectedSubscriptions array
            if (selectedSubscriptions.includes(checkboxValue)) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
            // Show the modal
        $('#editOfficerModal').modal('show');
    });
});


$('#checkAll').click(function() {
    $('input[type="checkbox"]:not(:disabled)').prop('checked', this.checked);
})
        
</script>
@endsection