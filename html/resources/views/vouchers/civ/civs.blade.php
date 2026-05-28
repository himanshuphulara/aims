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
            <x-bar-after-top-bar title="{{ $title }}" button="Add CIV" count="0" target="addciv" currentmonth=""/>
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addciv"><i class="las la-plus me-1"></i> Add Voucher</button>
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
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="mytab">
                                    <tr>
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Voucher</th>
                                                <th>Issue Date</th>
                                                <th>Issue Unit</th>
                                                <th>Issue Station</th>
                                                <th>Receipt</th>
                                                <th>Receipt Date</th>
                                                <th>Receipt Unit</th>
                                                <th>Receipt Station</th>
                                                <th>LPNO</th>
                                                <th>Items</th>
                                                <th>A/U</th>
                                                <th>Date</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Amt</th>
                                            </tr>
                                        </thead>
                                    </tr>
                                    <tbody>
                                        @forelse ($civs as $civ)
                                        @php 
                                        $civdata = \App\Models\Items::where('parent_item_id',$civ->id)->where('property_type','civs')->get();
                                        $rowspan = count($civdata);
                                        @endphp
                                        <tr>
                                            <td rowspan="{{$rowspan+1}}" class="text-center">
                                                <a href="{{ route('civdelete',[$civ->id]) }}" class="btn btn-danger btn-sm" id="{{ $civ->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
                                                <a href="{{ route('civedit',[$civ->id]) }}" class="btn btn-primary btn-sm" id="{{ $civ->id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                            </td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->issue_voc_no }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->issue_date }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->issue_unit }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->issue_station }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->receipt_voc_no }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->receipt_date }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->receipt_unit }}</td>
                                            <td rowspan="{{$rowspan+1}}">{{ $civ->receipt_station }}</td>
                                            @foreach($civdata as $item)
                                            <tr class="table-secondary">
                                                <td>{{ $item['lpno'] }}</td>
                                                <td>{{ $item['items'] }}</td>
                                                <td>{{ $item['au'] }}</td>
                                                <td class="text-center">{{ $item['date'] }}</td>
                                                <td class="text-center">{{ $item['qty'] }}</td>
                                                <td class="text-center">{{ $item['rate'] }}</td>
                                                <td class="text-center">{{ $item['amt'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tr>
                                        @empty
                                        <tr class="text-center"><td colspan="21">No CIV Founds</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                    @if(auth()->user()->hasPermissions('civledger'))
                    <a class="btn btn-sm btn-primary" href="{{ route('civledger',[request()->route()->parameters['cat_id']]) }}">CIV Ledger</a>
                    @endif
                </div>
            </div>
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<!-- Modal -->
<div class="modal fade" id="addciv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <style>
        legend{font-size: 20px;}
        .table-container {
            height: 300px; /* Set to desired height */
            overflow-y: auto; /* Enable vertical scrolling */
            border: 1px solid #ddd;
        }
    </style>
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Receipt, Issue And Expense Voucher</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="civ-form" action="{{route('civadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $cat_id }}" name="cat_id">
                {{-- <input type="hidden" value="{{ $id }}" name="voc_id"> --}}
                <div class="row">
                    <div class="col-md-6">
                        <p>To Be Completed By Issuing Officer</p>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="text" id="issue_voc_no" name="issue_voc_no" placeholder="Issue Voucher No.">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="date" id="issue_date" name="issue_date" placeholder="Date">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="text" id="issue_unit" name="issue_unit" placeholder="Unit">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="text" id="issue_station" name="issue_station" placeholder="Station">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>To Be Completed By Receiving Officer</p>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="text" id="receipt_voc_no" name="receipt_voc_no" placeholder="Receipt Voucher No.">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="date" id="receipt_date" name="receipt_date" placeholder="Date">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="text" id="receipt_unit" name="receipt_unit" placeholder="Unit">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light" type="text" id="receipt_station" name="receipt_station" placeholder="Station">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <b>Issue to: Charge off from Amenity (Public Fund) on approval of ASTB Mar 2021 from ledger charge.</b>
                        <p>In compliance with: The articles enumerated have been expended under the authority of (C)</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                        <div class="card-body table-container">
                        <table class="table table-bordered table-hover table-nowrap align-middle mb-0" id="myTable">
                            <thead>
                            <tr>
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
                                <tr>
                                    <td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>
                                    <td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>
                                    <td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>
                                    <td><input type="date" name="date[]" class="form-control" placeholder="Date"></td>
                                    <td><input type="number" min="1" name="qty[]" class="form-control qty" placeholder="Qty"></td>
                                    <td><input type="number" min="1" name="rate[]" class="form-control rate" placeholder="Rate"></td>
                                    <td><input type="number" min="1" name="amt[]" class="form-control amt" placeholder="Amount"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary btn-sm" id="addRow">Add Row</button>
                        <input type="hidden" id="jsonData" name="jsonData">
                        </div>
                        </div>
                        <p class="text-center">( Total items <u id="toatlqty">0</u> only)</p>
                        <p class="text-center">"Certified that above items have been charge off from ledger by means of this CIV</p>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="civ_upload" class="form-label"><b>Upload File:</b></label>
                            <input class="form-control" type="file" id="civ_upload" name="civ_upload" placeholder="Upload File">
                        </div>
                    </div>
                </div>
                
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="submit">Submit CIV</button>
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    $('#addRow').on('click', function() {
                        $('#myTable tbody').append(
                            '<tr>' +
                            '<td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>' +
                            '<td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>' +
                            '<td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>' +
                            '<td><input type="date" name="date[]" class="form-control" placeholder="Date"></td>' +
                            '<td><input type="number" min="1" name="qty[]" class="form-control qty" placeholder="Qty"></td>' +
                            '<td><input type="number" min="1" name="rate[]" class="form-control rate" placeholder="Rate"></td>' +
                            '<td><input type="number" min="1" name="amt[]" class="form-control amt" placeholder="Amount"></td>' +
                            '<td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>' +
                            '</tr>'
                        );
                    });
        
                    $('#myTable').on('click', '.removeRow', function() {
                        $(this).closest('tr').remove();
                        calculateSum();
                    });
                });
            </script>
        </div>
      </div>
    </div>
  </div>

<script>
$(document).ready(function() {
    $('#civ-form').on('submit', function(event) {
        event.preventDefault();
        // Gather table data and convert to JSON
        var tableData = [];
                $('#myTable tbody tr').each(function() {
                    var row = $(this);
                    var lpno = row.find('input[name="lpno[]"]').val();
                    var items = row.find('input[name="items[]"]').val();
                    var au = row.find('input[name="au[]"]').val();
                    var date = row.find('input[name="date[]"]').val();
                    var qty = row.find('input[name="qty[]"]').val();
                    var rate = row.find('input[name="rate[]"]').val();
                    var amt = row.find('input[name="amt[]"]').val();
                    
                    tableData.push({ lpno:lpno,items:items,au:au,date:date,qty:qty,rate:rate,amt:amt });
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