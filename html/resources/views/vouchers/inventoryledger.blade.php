@php use App\Helpers\Helper; @endphp
@extends('layouts.master')
@section('maincontent')
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <style>
    .date-range-picker {
        display: flex;
        align-items: center;
    }    
    .date-range-picker label {
        margin-right: 10px; 
    }    
    .date-range-picker input[type="date"] {
        width: auto;
        margin-right: 10px; 
    }    
    .btn {
        margin-left: 10px;
    }
    .num {
      mso-number-format:General;
    }
    .textdate{
      mso-number-format:"\@";/*force text*/
    }
    div#tbs {
        height: 500px;
        overflow-y: auto;
        overflow-x: auto;
    }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            @php $button = auth()->user()->hasPermissions('crvadd')?'Add Property':''; @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{ count($items)>0?count($items):0 }}" target="addcrv" currentmonth=""/>
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
                <div class="col-xs-6">
                        @php 
                            if(request('start_date')&& request('end_date')){
                                $start = request('start_date'); 
                                $end = request('end_date');
                            }else{
                                $start = date('Y-m-01'); 
                                $end = date('Y-m-t');
                            } 
                            $search = request('search')?request('search'):'';                            
                        @endphp
                    <form action="{{ route('fundvouchers',[request()->route()->parameters['id'],$fundfor]) }}">
                        <div class="date-range-picker float-start">
                            <div class="col-xs-2">
                            <label for="start-date">Start Date:</label>
                            <input type="date" class="form-control" name="start_date" id="start-date" value="{{ $start }}">   
                            </div>
                            <div class="col-xs-2">                     
                            <label for="end-date">End Date:</label>
                            <input type="date" class="form-control" name="end_date" id="end-date" value="{{ $end }}">   
                            </div>   
                            <div class="col-xs-2">
                            <label for="start-date">Search</label>
                            <input type="text" class="form-control" name="search" id="search" value="{{ $search }}">    
                            </div>                                       
                            <button id="submit" class="btn btn-sm btn-primary mt-4"><i class="me-1 las la-search"></i></button>                                                    
                            <a href="{{ route('fundvouchers',[request()->route()->parameters['id']]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                <i class="las la-redo-alt"></i>
                            </a>                                                    
                        </div>                                                     
                        </form>  
                        
                        {{-- <form action="{{ route('crvledgerdatadownload',[request()->route()->parameters['cat_id'],$fundfor]) }}" method="POST" class="float-end">
                                @csrf
                                <input type="date" class="form-control d-none" name="start_date" id="start-date" value="{{ $start }}">                    
                                <input type="date" class="form-control d-none" name="end_date" id="end-date" value="{{ $end }}">
                                <input type="text" class="form-control d-none" name="search" id="search" value="{{ $search }}">                                             
                                <button id="submit" class="btn btn-sm btn-primary mt-4"><i class="me-1 las la-file-export"></i>Download</button>                                                      
                        </form> --}}
                        <button id="btnExport" class="btn btn-sm btn-primary float-end mt-4" start-date="{{ $start }}" end-date="{{ $end }}" type="{{ $title }}"><i class="me-1 las la-file-export"></i> Export</button> 
                        <button id="blankExport" class="btn btn-sm btn-primary float-end mt-4" start-date="{{ $start }}" end-date="{{ $end }}" type="{{ $title }}"><i class="me-1 las la-file-export"></i> Export Blank Sheet</button> 
                        <a href="{{ route('civ',[request()->route()->parameters['id']]) }}" class="btn btn-primary btn-sm mt-4 float-end">CIV Voucher</a>
                        <a href="{{ route('niv',[request()->route()->parameters['id']]) }}" class="btn btn-primary btn-sm mt-4 float-end">NIV Voucher</a>
                        <a href="{{ route('crv',[request()->route()->parameters['id']]) }}" class="btn btn-primary btn-sm mt-4 float-end">CRV Voucher</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card" id="tables-container">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="mytab" border=1>
                                    <thead>
                                        <tr>
                                            @if(auth()->user()->hasPermissions('crvdelete') || auth()->user()->hasPermissions('crvedit'))
                                            <th rowspan="3" class="fixed text-center align-top">Action</th>
                                            @endif
                                            <th rowspan="3" class="text-center align-top">S.No</th>
                                            <th rowspan="3" class="text-center align-top">L/P No</th>
                                            <th rowspan="3" class="text-center align-top">Nomenclature</th>
                                            <th rowspan="3" class="text-center align-top">A/U</th>
                                            <th rowspan="3" class="text-center align-top">Yr/Dt of Purchase</th>
                                            <th colspan="2" class="text-center align-top">Qty Held On Charge</th>
                                            <th colspan="2" class="text-center align-top">Cost as per ASTB</th>
                                            <th colspan="4" class="text-center align-top">Qty</th>
                                            <th colspan="2" class="text-center align-top">Dep</th>                                                
                                            <th rowspan="3" class="text-center align-top">Amt after Depr (in Rs)</th>
                                            <th rowspan="3" class="text-center align-top">Cost of UNSV Items (in Rs)</th>
                                            <th rowspan="3" class="text-center align-top">Present Value (in Rs)</th>
                                            <th rowspan="3" class="text-center align-top">R by the BOO</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" class="text-center align-top">As Per Ledger</th>
                                            <th rowspan="2" class="text-center align-top">Grnd Bal</th>
                                            <th rowspan="2" class="text-center align-top">Rate (in Rs)</th>
                                            <th rowspan="2" class="text-center align-top">Amount (in Rs)</th>
                                            <th rowspan="2" class="text-center align-top">Serviceable</th>
                                            <th colspan="3" class="text-center align-top">UnServiceable</th>
                                            <th rowspan="2" class="text-center align-top">Dep %</th>
                                            <th rowspan="2" class="text-center align-top">Dep Amount</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Repairable</th>
                                            <th class="text-center">Auction</th>
                                            <th class="text-center">Destroy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $srno = 1; @endphp
                                        <tr style="text-align:center">
                                            <td></td>
                                            @foreach(range('a','s') as $al)
                                                <td>({{ $al }})</td>
                                            @endforeach
                                        </tr>
                                        @forelse ($items as $item)
                                            <tr class="@if($item->astb_done==1) table-warning @else table-secondary @endif">
                                                <td class="fixed"> 
                                                    <a href="{{ route('getastb',[$item->id,$start,$end]) }}" class="btn btn-primary btn-sm" id="{{ $item->id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>                                                       
                                                </td>
                                                <td style="text-align:center">{{ $srno++ }}</td>
                                                <td style="text-align:center">{{ $item['lpno'] }}</td>
                                                <td style="text-align:center">{{ $item['items'] }}</td>
                                                <td style="text-align:center">{{ $item['au'] }}</td>
                                                <td style="text-align:center">{{ date('d-m-Y',strtotime($item['date'])) }}</td>
                                                <td style="text-align:center">{{ $item['qty'] }}</td>
                                                <td style="text-align:center">{{ $item['qty'] }}</td>
                                                <td style="text-align:center">{{ Helper::numberFormat($item['rate']) }}</td>
                                                <td style="text-align:center">{{ Helper::numberFormat($item['amt']) }}</td>
                                                <td style="text-align:center">{{ $item['serviceable'] }}</td>
                                                <td style="text-align:center">{{ $item['repairable'] }}</td>
                                                <td style="text-align:center">{{ $item['auction'] }}</td>
                                                <td style="text-align:center">{{ $item['destroyable'] }}</td>
                                                <td style="text-align:center">{{ $item['dep_per'] }}</td>
                                                <td style="text-align:right">{{ Helper::numberFormat($item['dep_amt']) }}</td>
                                                <td style="text-align:right">{{ Helper::numberFormat($item['amt_after_depr']) }}</td>
                                                <td style="text-align:right">{{ Helper::numberFormat($item['unsv_items_amt']) }}</td>
                                                <td style="text-align:right">{{ Helper::numberFormat($item['pre_value']) }}</td>
                                                <td class="text-center">{{ $item['rboo'] }}</td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="19" class="text-center">No Record Found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <table id="date-range-table" style="width:100%; border-collapse:collapse;" class="d-none">
                                    <tr>
                                        <td colspan="20" style="text-align:center;font-size:18px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                            Date Range: <span id="date-range">{{ $start }} To {{ $end }}</span>
                                            @if($search!='')
                                                / <span style="margin-left:10px">Data Based On Search Keyword : {{ $search }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- end table responsive -->


                            {{-- For Blank Sheet Data --}}

                            <div class="table-responsive table-card d-none" id="blank-tables-container">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="mytab" border=1>
                                    <thead>
                                        <tr>
                                            <th rowspan="3" class="text-center align-top">Action</th>
                                            <th rowspan="3" class="text-center align-top">S.No</th>
                                            <th rowspan="3" class="text-center align-top">L/P No</th>
                                            <th rowspan="3" class="text-center align-top">Nomenclature</th>
                                            <th rowspan="3" class="text-center align-top">A/U</th>
                                            <th rowspan="3" class="text-center align-top">Yr/Dt of Purchase</th>
                                            <th colspan="2" class="text-center align-top">Qty Held On Charge</th>
                                            <th colspan="2" class="text-center align-top">Cost as per ASTB</th>
                                            <th colspan="4" class="text-center align-top">Qty</th>
                                            <th colspan="2" class="text-center align-top">Dep</th>                                                
                                            <th rowspan="3" class="text-center align-top">Amt after Depr (in Rs)</th>
                                            <th rowspan="3" class="text-center align-top">Cost of UNSV Items (in Rs)</th>
                                            <th rowspan="3" class="text-center align-top">Present Value (in Rs)</th>
                                            <th rowspan="3" class="text-center align-top">R by the BOO</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" class="text-center align-top">As Per Ledger</th>
                                            <th rowspan="2" class="text-center align-top">Grnd Bal</th>
                                            <th rowspan="2" class="text-center align-top">Rate (in Rs)</th>
                                            <th rowspan="2" class="text-center align-top">Amount (in Rs)</th>
                                            <th rowspan="2" class="text-center align-top">Serviceable</th>
                                            <th colspan="3" class="text-center align-top">UnServiceable</th>
                                            <th rowspan="2" class="text-center align-top">Dep %</th>
                                            <th rowspan="2" class="text-center align-top">Dep Amount</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Repairable</th>
                                            <th class="text-center">Auction</th>
                                            <th class="text-center">Destroy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $srno = 1; @endphp
                                        <tr style="text-align:center">
                                            <td></td>
                                            @foreach(range('a','s') as $al)
                                                <td>({{ $al }})</td>
                                            @endforeach
                                        </tr>
                                        @forelse ($items as $item)
                                            <tr class="table-secondary">
                                                <td class="fixed"></td>
                                                <td style="text-align:center">{{ $srno++ }}</td>
                                                <td style="text-align:center">{{ $item['lpno'] }}</td>
                                                <td style="text-align:center">{{ $item['items'] }}</td>
                                                <td style="text-align:center">{{ $item['au'] }}</td>
                                                <td style="text-align:center">{{ date('d-m-y',strtotime($item['date'])) }}</td>
                                                <td style="text-align:center">{{ $item['qty'] }}</td>
                                                <td style="text-align:center">{{ $item['qty'] }}</td>
                                                <td style="text-align:center">{{ Helper::numberFormat($item['rate']) }}</td>
                                                <td style="text-align:center">{{ Helper::numberFormat($item['amt']) }}</td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                                <td style="text-align:center"></td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="19" style="text-align:center">No Record Found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <table id="date-range-table" style="width:100%; border-collapse:collapse;">
                                    <tr>
                                        <td colspan="20" style="text-align:center;font-size:18px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                            Date Range: <span id="date-range">{{ $start }} To {{ $end }}</span>
                                            @if($search!='')
                                                / <span style="margin-left:10px">Data Based On Search Keyword : {{ $search }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- end table responsive -->

                            {{-- For Blank Sheet Data --}}
                        </div>
                    </div>
                    @if(auth()->user()->hasPermissions('crvledger'))
                        {{-- <a class="btn btn-sm btn-primary" href="{{ route('crvledger',[request()->route()->parameters['id']]) }}">CRV Ledger</a> --}}
                        {{-- <a class="btn btn-sm btn-primary" href="{{ route('crvledger',[request()->route()->parameters['id'],$fundfor]) }}">Download Ledger</a> --}}
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
    <div class="modal-dialog modal-xl" style="width: 100%">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Receipt, Issue And Expense Voucher</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- <div class="btn-group-vertical form-control" role="group" aria-label="Basic example">
                <a href="{{ route('crv',[request()->route()->parameters['id']]) }}" class="btn btn-primary mb-1">CRV Voucher</a>
                <a href="{{ route('niv',[request()->route()->parameters['id']]) }}" class="btn btn-primary mb-1">NIV Voucher</a>
                <a href="{{ route('civ',[request()->route()->parameters['id']]) }}" class="btn btn-primary">CIV Voucher</a>
            </div> --}}
            <select class="form-control" id="item-vouchers">
                <option value="">Select Voucher</option>
                <option value="crvs">CRV</option>
                <option value="nivs">NIV</option>
                <option value="civs">CIV</option>
            </select>
            {{-- CRV Vouchers --}}
            <div id="crvs-vouchers" style="display: none">
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
                        <table class="table table-bordered table-hover table-nowrap align-middle mb-0" id="myTableCrv">
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
                                    <td><input type="date" name="date[]" class="form-control" value="{{ $start }}" placeholder="Date"></td>
                                    <td><input type="number" min="1" name="qty[]" class="form-control crvqty" placeholder="Qty"></td>
                                    <td><input type="number" min="1" name="rate[]" class="form-control crvrate" placeholder="Rate"></td>
                                    <td><input type="number" min="1" name="amt[]" class="form-control crvamt" placeholder="Amount"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeCrvRow">-</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary btn-sm" id="addCrvRow">Add Row</button>
                        <input type="hidden" id="jsonCrvData" name="jsonData">
                        </div>
                        </div>
                        <p class="text-center">( Total items <u id="totalcrvqty">0</u> only)</p>
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
                    $('#addCrvRow').on('click', function() {
                        var start = $('#start-date').val();
                            const newTr = `<tr>
                            <td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>
                            <td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>
                            <td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>
                            <td><input type="date" name="date[]" value="${start}" class="form-control" placeholder="Date"></td>
                            <td><input type="number" min="1" name="qty[]" class="form-control crvqty" placeholder="Qty"></td>
                            <td><input type="number" min="1" name="rate[]" class="form-control crvrate" placeholder="Rate"></td>
                            <td><input type="number" min="1" name="amt[]" class="form-control crvamt" placeholder="Amount"></td>
                            <td><button type="button" class="btn btn-danger btn-sm removeCrvRow">-</button></td>
                            </tr>`;
                            $('#myTableCrv tbody').append(newTr);
                        const newRow = $('#myTableCrv tbody tr:last-child'); // Select the last added row
                        setTimeout(() => {
                            newRow[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 0);
                    });
        
                    $('#myTableCrv').on('click', '.removeCrvRow', function() {
                        $(this).closest('tr').remove();
                        calculatecrvSum();
                    });
                });
            </script>
            </div>
            {{-- CRV Vouchers --}}

            {{-- NIV Vouchers --}}
            <div id="nivs-vouchers" style="display: none">
            <form id="niv-form" action="{{route('nivadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $cat_id }}" name="cat_id">
                {{--<input type="hidden" value="{{ $id }}" name="voc_id">--}}
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
                        <b>Issue to: Items issued as per remarks</b>
                        <p>In compliance with: Issue of items procured from Public Fund.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                        <div class="card-body table-container">
                        <table class="table table-bordered table-hover table-nowrap align-middle mb-0" id="myTableNiv">
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
                                    <td><input type="date" name="date[]" value="{{ $start }}" class="form-control" placeholder="Date"></td>
                                    <td><input type="number" min="1" name="qty[]" class="form-control nivqty" placeholder="Qty"></td>
                                    <td><input type="number" min="1" name="rate[]" class="form-control nivrate" placeholder="Rate"></td>
                                    <td><input type="number" min="1" name="amt[]" class="form-control nivamt" placeholder="Amount"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeNivRow">-</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary btn-sm" id="addNivRow">Add Row</button>
                        <input type="hidden" id="jsonNivData" name="jsonData">
                        </div>
                        </div>
                        <p class="text-center">( Total items <u id="totalnivqty">0</u> only)</p>
                        <p class="text-center">PI return one copy duly receipted</p>
                        <div class="row">
                            <div class="col-md-4">
                                <b>Issued by</b>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="text" id="issued_by" name="issued_by" placeholder="Issued by">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b>Collected by</b>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="text" id="sig" name="sig" placeholder="Sig">
                                </div>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="text" id="no" name="no" placeholder="No">
                                </div>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="text" id="rank" name="rank" placeholder="Rank">
                                </div>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="text" id="name" name="name" placeholder="Name">
                                </div>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="date" id="date" name="date" placeholder="Date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b>Received by</b>
                                <div class="mb-2">
                                    <input class="form-control bg-light" type="text" id="received_by" name="received_by" placeholder="Received by">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="niv_upload" class="form-label"><b>Upload File:</b></label>
                                <input class="form-control" type="file" id="niv_upload" name="niv_upload" placeholder="Upload File">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="submit">Submit NIV</button>
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    $('#addNivRow').on('click', function() {
                        var start = $('#start-date').val();
                            const newTr = `<tr>
                            <td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>
                            <td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>
                            <td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>
                            <td><input type="date" name="date[]" value="${start}" class="form-control" placeholder="Date"></td>
                            <td><input type="number" min="1" name="qty[]" class="form-control nivqty" placeholder="Qty"></td>
                            <td><input type="number" min="1" name="rate[]" class="form-control nivrate" placeholder="Rate"></td>
                            <td><input type="number" min="1" name="amt[]" class="form-control nivamt" placeholder="Amount"></td>
                            <td><button type="button" class="btn btn-danger btn-sm removeNivRow">-</button></td>
                            </tr>`;
                            $('#myTableNiv tbody').append(newTr);
                        const newRow = $('#myTableNiv tbody tr:last-child'); // Select the last added row
                        setTimeout(() => {
                            newRow[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 0);
                    });
        
                    $('#myTableNiv').on('click', '.removeNivRow', function() {
                        $(this).closest('tr').remove();
                        calculatenivSum();
                    });
                });
            </script>
            </div>
            {{-- NIV Vouchers --}}

            {{-- CIV Vouchers --}}
            <div id="civs-vouchers" style="display: none">
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
                        <table class="table table-bordered table-hover table-nowrap align-middle mb-0" id="myTableCiv">
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
                                    <td><input type="date" name="date[]" value={{ $start }} class="form-control" placeholder="Date"></td>
                                    <td><input type="number" min="1" name="qty[]" class="form-control civqty" placeholder="Qty"></td>
                                    <td><input type="number" min="1" name="rate[]" class="form-control civrate" placeholder="Rate"></td>
                                    <td><input type="number" min="1" name="amt[]" class="form-control civamt" placeholder="Amount"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeCivRow">-</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary btn-sm" id="addCivRow">Add Row</button>
                        <input type="hidden" id="jsonCivData" name="jsonData">
                        </div>
                        </div>
                        <p class="text-center">( Total items <u id="totalcivqty">0</u> only)</p>
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
                    $('#addCivRow').on('click', function() {
                        var start = $('#start-date').val();
                            const newTr = `<tr>
                            <td><input type="text" name="lpno[]" class="form-control" placeholder="LP No"></td>
                            <td><input type="text" name="items[]" class="form-control" placeholder="Item Name"></td>
                            <td><input type="text" name="au[]" class="form-control" placeholder="A/U"></td>
                            <td><input type="date" name="date[]" value="${start}" class="form-control" placeholder="Date"></td>
                            <td><input type="number" min="1" name="qty[]" class="form-control civqty" placeholder="Qty"></td>
                            <td><input type="number" min="1" name="rate[]" class="form-control civrate" placeholder="Rate"></td>
                            <td><input type="number" min="1" name="amt[]" class="form-control civamt" placeholder="Amount"></td>
                            <td><button type="button" class="btn btn-danger btn-sm removeCivRow">-</button></td>
                            </tr>`;
                            $('#myTableCiv tbody').append(newTr);
                            const newRow = $('#myTableCiv tbody tr:last-child'); // Select the last added row
                            setTimeout(() => {
                                newRow[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }, 0);
                    });
        
                    $('#myTableCiv').on('click', '.removeCivRow', function() {
                        $(this).closest('tr').remove();
                        calculatecivSum();
                    });
                });
            </script>
            </div>
            {{-- CIV Vouchers --}}
        </div>
      </div>
    </div>
  </div>

<script>
$(document).ready(function() {
    $('#item-vouchers').on('change',function(){
        var voucher = $(this).val();
        if(voucher=='crvs'){
            $('#crvs-vouchers').show();
            $('#nivs-vouchers').hide();
            $('#civs-vouchers').hide();
        }else if(voucher=='nivs'){
            $('#crvs-vouchers').hide();
            $('#nivs-vouchers').show();
            $('#civs-vouchers').hide();
        }else if(voucher=='civs'){
            $('#crvs-vouchers').hide();
            $('#nivs-vouchers').hide();
            $('#civs-vouchers').show();
        }
    });
    //Crv Form
    $('#crv-form').on('submit', function(event) {
        event.preventDefault();
        // Gather table data and convert to JSON
        var tableData = [];
        $('#myTableCrv tbody tr').each(function() {
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
        $('#jsonCrvData').val(JSON.stringify(tableData));
        // Submit the form
        this.submit();
    });
    //Niv Form
    $('#niv-form').on('submit', function(event) {
        event.preventDefault();
        // Gather table data and convert to JSON
        var tableData = [];
        $('#myTableNiv tbody tr').each(function() {
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
        $('#jsonNivData').val(JSON.stringify(tableData));
        // Submit the form
        this.submit();        
    });
    //Civ Form
    $('#civ-form').on('submit', function(event) {
        event.preventDefault();
        // Gather table data and convert to JSON
        var tableData = [];
        $('#myTableCiv tbody tr').each(function() {
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
        $('#jsonCivData').val(JSON.stringify(tableData));
        // Submit the form
        this.submit();        
    });
});

//Crv Calculation qty rate amt
$(document).on('input', '.crvqty, .crvrate', function() {
    var $row = $(this).closest('tr');
    var qty = parseFloat($row.find('.crvqty').val()) || 0; 
    var rate = parseFloat($row.find('.crvrate').val()) || 0; 
    var amt = qty * rate;
    $row.find('.crvamt').val(amt.toFixed(2)); 
});
function calculatecrvSum() {
    let sum = 0;
    $('.crvqty').each(function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
                sum += value; 
            }
    });
    $('#totalcrvqty').text(sum);
}
$(document).on('input', '.crvqty', calculatecrvSum);
calculatecrvSum();

//Niv Calculation qty rate amt
$(document).on('input', '.nivqty, .nivrate', function() {
    var $row = $(this).closest('tr');
    var qty = parseFloat($row.find('.nivqty').val()) || 0; 
    var rate = parseFloat($row.find('.nivrate').val()) || 0; 
    var amt = qty * rate;
    $row.find('.nivamt').val(amt.toFixed(2)); 
});
function calculatenivSum() {
    let sum = 0;
    $('.nivqty').each(function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
                sum += value; 
            }
    });
    $('#totalnivqty').text(sum);
}
$(document).on('input', '.nivqty', calculatenivSum);
calculatenivSum();

//Civ Calculation qty rate amt
$(document).on('input', '.civqty, .civrate', function() {
    var $row = $(this).closest('tr');
    var qty = parseFloat($row.find('.civqty').val()) || 0; 
    var rate = parseFloat($row.find('.civrate').val()) || 0; 
    var amt = qty * rate;
    $row.find('.civamt').val(amt.toFixed(2)); 
});
function calculatecivSum() {
    let sum = 0;
    $('.civqty').each(function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
                sum += value; 
            }
    });
    $('#totalcivqty').text(sum);
}
$(document).on('input', '.civqty', calculatecivSum);
calculatecivSum();

// $(document).on('input', '#issue_unit', function() {
//     $('#unitname').text($(this).val()) 
//     $('#receipt_unit').val($(this).val()) 
// });

</script>


<script src="{{ asset('assets/js/newexcelexport.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#btnExport").click(function() {
            start = $(this).attr('start-date');
            end = $(this).attr('end-date');
            type = $(this).attr('type');
            // Create a new workbook
            var excelData = '';

            var dateRangeHtml = $('#date-range-table').prop('outerHTML');
            excelData += dateRangeHtml + '<br/><br/>'; // Add space after the date range
            
            // Loop through each table and append its HTML to the excelData string
            $('#tables-container table').each(function() {
                var tableHtml = $(this).prop('outerHTML');

                 // Remove hyperlinks from the table
                 tableHtml = $(tableHtml).find('a').each(function() {
                    $(this).replaceWith($(this).text()); // Replace link with its text
                }).end().prop('outerHTML');
                
                excelData += tableHtml + '<br/><br/>'; // Add space between tables
            });

            // Create a temporary link to download the Excel file
            var blob = new Blob([excelData], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'
            });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = `${type}-${start}-${end}.xlsx`;
            a.click();
            URL.revokeObjectURL(url);
        });
      
    });
</script>

<script>
    $(document).ready(function() {
        $("#blankExport").click(function() {
            start = $(this).attr('start-date');
            end = $(this).attr('end-date');
            type = $(this).attr('type');
            // Create a new workbook
            var excelData = '';

            var dateRangeHtml = $('#date-range-table').prop('outerHTML');
            excelData += dateRangeHtml + '<br/><br/>'; // Add space after the date range
            
            // Loop through each table and append its HTML to the excelData string
            $('#blank-tables-container table').each(function() {
                var tableHtml = $(this).prop('outerHTML');

                 // Remove hyperlinks from the table
                 tableHtml = $(tableHtml).find('a').each(function() {
                    $(this).replaceWith($(this).text()); // Replace link with its text
                }).end().prop('outerHTML');
                
                excelData += tableHtml + '<br/><br/>'; // Add space between tables
            });

            // Create a temporary link to download the Excel file
            var blob = new Blob([excelData], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'
            });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = `Blank-Sheet-${type}-${start}-${end}.xlsx`;
            a.click();
            URL.revokeObjectURL(url);
        });
      
    });
</script>
@endsection