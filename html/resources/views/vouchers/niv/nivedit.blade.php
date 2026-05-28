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
            <a href="{{ route('nivpdf',[request()->route()->parameters['niv_id']]) }}" class="btn btn-sm btn-primary float-end">Download NIV Pdf</a>
            <x-bar-after-top-bar title="{{ $title }}" button="" count="0" target="" currentmonth=""/>
            <!-- start page title -->
            {{-- <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }}</h4>
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center float-end">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addniv"><i class="las la-plus me-1"></i> Add Voucher</button>
                    </div>
                </div>
                @endif
            </div> --}}
            <!-- end page title -->
            <form id="niv-form" action="{{route('nivupdate')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $niv_id }}" name="niv_id">
                <input type="hidden" value="{{ $nivs->category_id }}" name="cat_id">
                <input type="hidden" value="{{ $nivs->voc_id }}" name="voc_id">
                <div class="row">
                    <div class="col-md-6">
                        <p>To Be Completed By Issuing Officer</p>
                        <div class="mb-2">
                            <label for="issue_voc_no" class="form-label">Issue Voucher No</label>
                            <input class="form-control" type="text" id="issue_voc_no" name="issue_voc_no" placeholder="Issue Voucher No." value="{{ $nivs->issue_voc_no }}">
                        </div>
                        <div class="mb-2">
                            <label for="issue_date" class="form-label">Date</label>
                            <input class="form-control" type="date" id="issue_date" name="issue_date" placeholder="Date" value="{{ $nivs->issue_date }}">
                        </div>
                        <div class="mb-2">
                            <label for="issue_unit" class="form-label">Issue Unit</label>
                            <input class="form-control" type="text" id="issue_unit" name="issue_unit" placeholder="Unit" value="{{ $nivs->issue_unit }}">
                        </div>
                        <div class="mb-2">
                            <label for="issue_station" class="form-label">Issue Station</label>
                            <input class="form-control" type="text" id="issue_station" name="issue_station" placeholder="Station" value="{{ $nivs->issue_station }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>To Be Completed By Receiving Officer</p>
                        <div class="mb-2">
                            <label for="receipt_voc_no" class="form-label">Receipt Vocucher No</label>
                            <input class="form-control" type="text" id="receipt_voc_no" name="receipt_voc_no" placeholder="Receipt Voucher No." value="{{ $nivs->receipt_voc_no }}">
                        </div>
                        <div class="mb-2">
                            <label for="receipt_date" class="form-label">Date</label>
                            <input class="form-control" type="date" id="receipt_date" name="receipt_date" placeholder="Date" value="{{ $nivs->receipt_date }}">
                        </div>
                        <div class="mb-2">
                            <label for="receipt_unit" class="form-label">Receipt Station</label>
                            <input class="form-control" type="text" id="receipt_unit" name="receipt_unit" placeholder="Unit" value="{{ $nivs->receipt_unit }}">
                        </div>
                        <div class="mb-2">
                            <label for="receipt_station" class="form-label">Receipt Station</label>
                            <input class="form-control" type="text" id="receipt_station" name="receipt_station" placeholder="Station" value="{{ $nivs->receipt_station }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <b>Issue to: Items issued as per remarks</b>
                        <p>In compliance with: Issue of items procured from Public Fund.</p>
                    </div>
                </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body table-container">
                            <div class="table-responsive table-card">
                                <table class="table table-bordered table-hover table-nowrap align-middle mb-0" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th class="d-none">Item</th>
                                            <th>LP No</th>
                                            <th>Items</th>
                                            <th>A/U</th>
                                            <th>Date</th>
                                            <th>Qty</th>
                                            <th>Rate(Rs)</th>
                                            <th>Amt(Rs)</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        @php 
                                            $nivdata = \App\Models\Items::where('parent_item_id',$nivs->id)->where('property_type','nivs')->get();
                                            $sno = 1;
                                        @endphp
                                        @foreach($nivdata as $item)
                                        <tr>
                                            <td id="sno">{{ $sno++ }}</td>
                                            <td class="d-none"><input type="hidden" name="itemid[]" class="form-control" placeholder="LP No" value="{{ $item['id'] }}"></td>
                                            <td><input type="text" name="lpno[]" class="form-control" placeholder="LP No" value="{{ $item['lpno'] }}"></td>
                                            <td><input type="text" name="items[]" class="form-control" placeholder="Item Name" value="{{ $item['items'] }}"></td>
                                            <td><input type="text" name="au[]" class="form-control" placeholder="A/U" value="{{ $item['au'] }}"></td>
                                            <td><input type="date" name="date[]" class="form-control" placeholder="Date" value="{{ $item['date'] }}"></td>
                                            <td><input type="number" name="qty[]" class="form-control qty" placeholder="Qty" value="{{ $item['qty'] }}"></td>
                                            <td><input type="number" step="0.01" name="rate[]" class="form-control rate" placeholder="Rate" value="{{ $item['rate'] }}"></td>
                                            <td><input type="number" step="0.01" name="amt[]" class="form-control amt" placeholder="Amount" value="{{ $item['amt'] }}" readonly></td>
                                            @if($item->astb_done==0)
                                            <td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary btn-sm m-2" id="addRow">Add Row</button>
                                <input type="hidden" id="jsonData" name="jsonData">
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                    <p class="text-center">( Total items <u id="toatlqty">0</u> only)</p>
                        <p class="text-center">PI return one copy duly receipted</p>
                        <div class="row">
                            <div class="col-md-4">
                                <b>Issued by</b>
                                <div class="mb-2">
                                    <input class="form-control" type="text" id="issued_by" name="issued_by" placeholder="Issued by" value="{{ $nivs->receipt_station }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b>Collected by</b>
                                <div class="form-group row">
                                    <label for="sig" class="col-sm-1 m-2 form-label"><b>Sig</b>:</label>
                                    <div class="col-sm-10">
                                    <input class="form-control" type="text" id="sig" name="sig" placeholder="Sig" value="{{ $nivs->sig }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no" class="col-sm-1 m-2 form-label"><b>No</b>:</label>
                                    <div class="col-sm-10">
                                    <input class="form-control" type="text" id="no" name="no" placeholder="No" value="{{ $nivs->no }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="rank" class="col-sm-1 m-2 form-label"><b>Rank</b>:</label>
                                    <div class="col-sm-10">
                                    <input class="form-control" type="text" id="rank" name="rank" placeholder="Rank" value="{{ $nivs->rank }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-1 m-2 form-label"><b>Name</b>:</label>
                                    <div class="col-sm-10">
                                    <input class="form-control" type="text" id="name" name="name" placeholder="Name" value="{{ $nivs->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="date" class="col-sm-1 m-2 form-label"><b>Date</b>:</label>
                                    <div class="col-sm-10">
                                    <input class="form-control" type="date" id="date" name="date" placeholder="Date" value="{{ $nivs->date }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b>Received by</b>
                                <div class="mb-2">
                                    <input class="form-control" type="text" id="received_by" name="received_by" placeholder="Received by" value="{{ $nivs->received_by }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="niv_upload" class="form-label"><b>Upload File:</b></label>
                                <input class="form-control" type="file" id="niv_upload" name="niv_upload" placeholder="Upload File">
                                @if($nivs->niv_upload!='')
                                <a href="{{ asset('storage/'.$nivs->niv_upload) }}" target="_blank">View</a> 
                                @endif
                            </div>
                        </div>
                </div>
            </div>
            <div class="mt-2">
                <button class="btn btn-primary w-100" id="submitBtn" type="submit">Update NIV</button>
            </div>
        </form>
            <script>
                $(document).ready(function() {
                    $('#addRow').on('click', function() {
                        var lastRow = $('#myTable tr:last');
                        var lastCell = lastRow.find('td:first');
                        var firstChildValue = parseInt(lastCell.contents().first().text())+1;
                            // console.log(firstChildValue++);
                            const newTr = '<tr>' +
                            '<td id="sno">'+(firstChildValue)+'</td>' +
                            '<td class="d-none"><input type="hidden" name="itemid[]" class="form-control" placeholder="Item Id"></td>' +
                            '<td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>' +
                            '<td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>' +
                            '<td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>' +
                            '<td><input type="date" name="date[]" class="form-control" placeholder="Date"></td>' +
                            '<td><input type="number" name="qty[]" class="form-control qty" placeholder="Qty"></td>' +
                            '<td><input type="number" step="0.01" name="rate[]" class="form-control rate" placeholder="Rate"></td>' +
                            '<td><input type="number" step="0.01" name="amt[]" class="form-control amt" placeholder="Amount" readonly></td>' +
                            '<td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>' +
                            '</tr>'
                            $('#myTable tbody').append(newTr);
                        const newRow = $('#myTable tbody tr:last-child'); // Select the last added row
                        setTimeout(() => {
                            newRow[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 0);
                    });
            
                    $('#myTable').on('click', '.removeRow', function() {
                        $(this).closest('tr').remove();
                        calculateSum();
                    });
                });
            </script>
            
            
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>



<script>
$(document).ready(function() {
    $('#niv-form').on('submit', function(event) {
        event.preventDefault();
        // Gather table data and convert to JSON
        var tableData = [];
                $('#myTable tbody tr').each(function() {
                    var row = $(this);
                    var itemid = row.find('input[name="itemid[]"]').val();
                    var lpno = row.find('input[name="lpno[]"]').val();
                    var items = row.find('input[name="items[]"]').val();
                    var au = row.find('input[name="au[]"]').val();
                    var date = row.find('input[name="date[]"]').val();
                    var qty = row.find('input[name="qty[]"]').val();
                    var rate = row.find('input[name="rate[]"]').val();
                    var amt = row.find('input[name="amt[]"]').val();
                    
                    tableData.push({ itemid:itemid,lpno:lpno,items:items,au:au,date:date,qty:qty,rate:rate,amt:amt });
                });

                // Set JSON data to hidden input
                $('#jsonData').val(JSON.stringify(tableData));

                // Submit the form
                this.submit();
        
    });
});

$(document).on('input', '.qty, .rate', function() {
    var $row = $(this).closest('tr');
    var qty = parseFloat($row.find('.qty').val()) || 0; 
    var rate = parseFloat($row.find('.rate').val()) || 0; 
    var amt = qty * rate;
    $row.find('.amt').val(amt.toFixed(2)); 
});
function calculateSum() {
    let sum = 0;
    $('.qty').each(function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
                sum += value; 
            }
    });
    $('#toatlqty').text(sum);
}
$(document).on('input', '.qty', calculateSum);
calculateSum();
</script>
@endsection