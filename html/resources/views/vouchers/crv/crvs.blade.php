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
            @php $button = auth()->user()->hasPermissions('crvadd')?'Add CRV':''; @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{ count($crvs) }}" target="addcrv" currentmonth=""/>
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addcrv"><i class="las la-plus me-1"></i> Add Voucher</button>
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
                                                @if(auth()->user()->hasPermissions('crvdelete') || auth()->user()->hasPermissions('crvedit'))
                                                <th class="fixed">Action</th>
                                                @endif
                                                <th>Issue Voucher</th>
                                                <th>Issue Expense</th>
                                                <th>Issue Unit</th>
                                                <th>Issue Station</th>
                                                <th>Receipt Voucher</th>
                                                <th>Receipt Date</th>
                                                <th>Receipt Unit</th>
                                                <th>Receipt Station</th>
                                                <th>Purchase From</th>
                                                <th>For By</th>
                                                <th>Bill No</th>
                                                <th>Gem</th>
                                                <th>Dt</th>
                                                <th>Contact No</th>
                                                <th>Gemcrac</th>
                                                <th>Dated</th>
                                                <th>Holder Sign</th>
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
                                        @php $srno = 1; @endphp
                                        @forelse ($crvs as $crv)
                                            @php
                                                $crvdata = \App\Models\Items::where('parent_item_id',$crv->id)->where('property_type','crvs')->get(); 
                                                $rowspan = count($crvdata);
                                            @endphp
                                           <tr>
                                                @if(auth()->user()->hasPermissions('nivdelete') || auth()->user()->hasPermissions('nivedit'))
                                                <td rowspan="{{$rowspan+1}}" class="text-center fixed">
                                                    @if(auth()->user()->hasPermissions('crvdelete'))
                                                        <a href="{{ route('crvdelete',[$crv->id]) }}" class="btn btn-danger btn-sm" id="{{ $crv->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
                                                    @endif
                                                    @if(auth()->user()->hasPermissions('crvedit'))
                                                        <a href="{{ route('crvedit',[$crv->id]) }}" class="btn btn-primary btn-sm" id="{{ $crv->id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    @endif
                                                </td>
                                                @endif
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->issue_voc_no }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->issue_expense }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->issue_unit }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->issue_station }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->receipt_voc_no }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->receipt_date }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->receipt_unit }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->receipt_station }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->purchase_from }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->for_fy }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->bill_no }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->gem }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->dt }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->contact_no }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->gemcrac }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->dated }}</td>
                                                <td rowspan="{{$rowspan+1}}" class="text-wrap">{{ $crv->holder_sign }}</td>
                                                @foreach($crvdata as $item)
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
                                            <tr class="text-center"><td colspan="8">No Record Founds</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                    @if(auth()->user()->hasPermissions('crvledger'))
                        {{-- <a class="btn btn-sm btn-primary" href="{{ route('crvledger',[request()->route()->parameters['id']]) }}">CRV Ledger</a> --}}
                        <a class="btn btn-sm btn-primary" href="{{ route('crvledger',[request()->route()->parameters['cat_id'],$fundfor]) }}">CRV Ledger</a>
                    @endif
                </div>
            </div>
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<!-- Modal -->
<div class="modal fade" id="addcrv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Receipt, Issue And Expense Voucher</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="crv-form" action="{{route('crvadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $cat_id }}" name="cat_id">
                {{-- <input type="hidden" value="{{ $id }}" name="voc_id"> --}}
                <div class="row">
                    <div class="col-md-6">
                        <p>To Be Completed By Issuing Officer</p>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="issue_voc_no" name="issue_voc_no" placeholder="Issue Voucher No.">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="issue_expense" name="issue_expense" placeholder="Expense">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="issue_unit" name="issue_unit" placeholder="Unit">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="issue_station" name="issue_station" placeholder="Station">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>To Be Completed By Receiving Officer</p>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="receipt_voc_no" name="receipt_voc_no" placeholder="Receipt Voucher No.">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="date" id="receipt_date" name="receipt_date" placeholder="Date">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="receipt_unit" name="receipt_unit" placeholder="Unit">
                        </div>
                        <div class="mb-2">
                            <input class="form-control bg-light border-0" type="text" id="receipt_station" name="receipt_station" placeholder="Station">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">Issued To</p>
                        <span>The articles enumerated below has been</span>
                        <p>(a) Received by</p>
                        
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="purchase_from" class="form-label">Manufactured: Purchase from</label>
                                <input type="text" class="form-control bg-light border-0" id="purchase_from" name="purchase_from" placeholder="Manufactured: Purchase from">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="for_fy" class="form-label">For the FY</label>
                                <input type="text" class="form-control bg-light border-0" id="for_fy" name="for_fy" placeholder="For the FY">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="bill_no" class="form-label">vide bill no</label>
                                <input class="form-control bg-light border-0" type="text" id="bill_no" name="bill_no" placeholder="vide bill no">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="gem" class="form-label">GEM</label>
                                <input class="form-control bg-light border-0" type="text" id="gem" name="gem" placeholder="GEM">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="dt" class="form-label">dt</label>
                                <input class="form-control bg-light border-0" type="date" id="dt" name="dt" placeholder="dt">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="contact_no" class="form-label">Contact No</label>
                                <input class="form-control bg-light border-0" type="text" id="contact_no" name="contact_no" placeholder="Contact No">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="gemcrac" class="form-label">GEMCRAC</label>
                                <input class="form-control bg-light border-0" type="text" id="gemcrac" name="gemcrac" placeholder="GEMCRAC">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="dated" class="form-label">Dated</label>
                                <input class="form-control bg-light border-0" type="date" id="dated" name="dated" placeholder="Dated">
                            </div>
                        </div>
                        <p>(b) In (a) part in compliance with (c) Sanction accorded by CO <b id="unitname"></b></p>
                        <p>The articles enumerated below have been explained under the authority of (c)</p>
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
                        <p class="text-center">"Certified that above items have been taken on ledger charge by means of this CRV"</p>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="holder_sign" class="form-label">Signature of store holder</label>
                                <input class="form-control bg-light border-0" type="text" id="holder_sign" name="holder_sign" placeholder="Signature of store holder">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="crv_upload" class="form-label"><b>Upload File:</b></label>
                                <input class="form-control" type="file" id="crv_upload" name="crv_upload" placeholder="Upload File">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="submit">Submit CRV</button>
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    $('#addRow').on('click', function() {
                            const newTr = '<tr>' +
                            '<td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>' +
                            '<td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>' +
                            '<td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>' +
                            '<td><input type="date" name="date[]" class="form-control" placeholder="Date"></td>' +
                            '<td><input type="number" min="1" name="qty[]" class="form-control qty" placeholder="Qty"></td>' +
                            '<td><input type="number" min="1" name="rate[]" class="form-control rate" placeholder="Rate"></td>' +
                            '<td><input type="number" min="1" name="amt[]" class="form-control amt" placeholder="Amount"></td>' +
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
      </div>
    </div>
  </div>

<script>
$(document).ready(function() {
    $('#crv-form').on('submit', function(event) {
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

$(document).on('input', '#issue_unit', function() {
    $('#unitname').text($(this).val()) 
    $('#receipt_unit').val($(this).val()) 
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