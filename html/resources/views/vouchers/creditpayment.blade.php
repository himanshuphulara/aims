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
    align-items: center; /* Aligns items vertically in the center */
}

.date-range-picker label {
    margin-right: 10px; /* Space between label and input */
}

.date-range-picker input[type="date"] {
    width: auto; /* Allow the input to size based on content */
    margin-right: 10px; /* Space between inputs */
}

.num {
  mso-number-format:General;
}
.textdate{
  mso-number-format:"\@";/*force text*/
}
div#tbs {
    height: 600px;
    overflow-y: auto;
    overflow-x: auto;
}
.thead{
    position: sticky;
    top: 0; /* Stick to the top */
    background-color: #f9f9f9; /* Background color for header */
    z-index: 1; /* Ensure it sits above other content */
}
/* Loader (spinner) style */
.loader {
  border: 4px solid rgba(255, 255, 255, 0.3); /* Light background */
  border-top: 4px solid #3498db; /* Blue color for the spinning part */
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%); /* Center the loader */
  display: none; /* Hidden initially */
}

/* Keyframes for spinning animation */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
    <div class="page-content">
        <div class="container-fluid">
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
            @php $button = auth()->user()->hasPermissions('voucheradd')?'Add Voucher':''; @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{ count($vouchers)>0?count($vouchers)-1:0 }}" target="addvoucher" currentmonth="Payment (Debit) MONTH OF {{ date('M, Y',strtotime($start)) }}"/>
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addvoucher"><i class="las la-plus me-1"></i> Add Voucher</button>
                    </div>
                </div>
                @endif
            </div> --}}
            <!-- end page title -->
            <div class="row">
                <div class="col-xs-6">
                    <form action="{{ route('voucherp',[request()->route()->parameters['id']]) }}">
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
                            <button id="submit" class="btn btn-sm btn-primary mt-4 me-2 ms-2"><i class="las la-search"></i></button>                                                    
                            <a href="{{ route('voucherp',[request()->route()->parameters['id']]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                <i class="las la-redo-alt"></i>
                            </a>                                                    
                        </div>                                                     
                    </form> 
                    <div class="float-end" role="group">
                        <a href="{{ route('chequelist',[request()->route()->parameters['id'],$start,$end]) }}" class="btn btn-primary btn-sm me-1 mt-4">Cheque</a>
                        <button id="addBbfBtn" class="btn btn-primary btn-sm mt-4" data-bs-toggle="modal" data-bs-target="#bbfmodal"><i class="las la-plus me-1"></i>Add BBF</button>
                        <button id="autoBbfBtn" class="btn btn-success btn-sm mt-4 me-2" data-bs-toggle="modal" data-bs-target="#bbfmodal"><i class="las la-magic me-1"></i>Auto BBF Debit</button>
                        <button type="button" class="btn btn-primary btn-sm mt-4 d-none">Sys Drs</button>
                        <button type="button" class="btn btn-primary btn-sm mt-4 d-none">Sys Crs</button>
                        <button id="btnExport" class="btn btn-sm btn-primary mt-4" start-date="{{ $start }}" end-date="{{ $end }}" month="Payment (Debit) MONTH OF {{ now()->format('M, Y') }}" fundtype="{{ $fundfor }}"><i class="me-1 las la-file-export"></i>Export</button> 
                    </div>                       
                </div>
            </div>
            @php
                // print_r($categories->toArray());
                $aslib = [];
                foreach($categories as $cate)
                {
                    $aslib[strtolower(str_replace(" ","_",$cate->name))] = strtolower($cate->type);
                }
                // print_r($aslib);
            @endphp
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card" id="tables-container">
                                <div id="tbs">
                                    <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="table1" border="1">
                                        <tr class="thead">
                                            @if(auth()->user()->hasPermissions('voucherdelete') || auth()->user()->hasPermissions('voucheredit'))
                                            <th class="align-top fixed">Action</th>
                                            @endif
                                            <th class="align-top">Date</th>
                                            <th class="align-top">Vr No</th>
                                            <th class="align-top">From whom recd</th>
                                            <th class="align-top">On what account</th>
                                            <th class="align-top">CASH (Rs)</th>
                                            <th class="align-top">BANK (Rs)</th>
                                            {{-- <th colspan="{{ count($categories) + 1 }}" class="text-center align-top">Credit to Ledger Accounts</th> --}}
                                        
                                            @foreach ($categories as $cate)
                                                <th class="align-top">{{ $cate->name }}</th>                                          
                                            @endforeach
                                            @if(request('id')==37)
                                            <th class="align-top">Memento Stock</th>
                                            @endif
                                            <th class="align-top">Property</th>
                                            <th class="align-top">FD</th>
                                            <th class="align-top fixed">Total</th> 
                                        </tr>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" style="text-align:center">bbf</td>
                                                <td class="text-end">{{ $voc_cash = isset($bbf['voc_cash'])?Helper::numberFormat($bbf['voc_cash']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end">{{ $voc_bank = isset($bbf['voc_bank'])?Helper::numberFormat($bbf['voc_bank']):Helper::numberFormat(0) }}</td>
                                                @if(isset($bbf['bfftotal']))
                                                @foreach($bbf['bfftotal'] as $key=>$value)
                                                    <td class="text-end">{{Helper::numberFormat($value)}}</td>
                                                @endforeach
                                                @endif
                                                @if(request('id')==37)
                                                <td class="text-end">{{ $voc_memo_stk = isset($bbf['voc_memo_stk'])?Helper::numberFormat($bbf['voc_memo_stk']):Helper::numberFormat(0) }}</td>
                                                @endif
                                                <td class="text-end">{{ $voc_property = isset($bbf['voc_property'])?Helper::numberFormat($bbf['voc_property']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end">{{ $voc_fd = isset($bbf['voc_fd'])?Helper::numberFormat($bbf['voc_fd']):Helper::numberFormat(0) }}</td>
                                            </tr>
                                            @php
                                                $cash=$bank=$memostk=$property=$fd=$v=0;
                                                $cattotal = [];
                                                $tables = [];$tab=0;
                                            @endphp
                                            @foreach($vouchers as $voc)
                                            @php
                                                $cash += $voc->voc_cash;
                                                $bank += $voc->voc_bank;
                                                $memostk += $voc->voc_memo_stk;
                                                $property += $voc->voc_property;
                                                $fd += $voc->voc_fd;
                                                //helper function for merging new and old categories with db 
                                                $voc_json =  \App\Helpers\Helper::categories_voc_json($voc->voc_json,$cat_id);
                                                // print_r($data);
                                                // print_r($voc->voc_json);die;
                                                foreach($voc_json as $key=>$value){
                                                    $keys = str_replace("_"," ",$key);
                                                    if($key!=''){
                                                        if (isset($cattotal[$key])) {
                                                            $cattotal[$key] += $value;
                                                            $tables[$keys] += $value;
                                                        } else {
                                                            $cattotal[$key] = $value;
                                                            $tables[$keys] = $value;
                                                       }
                                                    }
                                                }
                                                // print_r($tables);die;
                                            @endphp
                                            @if($voc->voucher_entery==1)
                                            <tr>
                                                @if(auth()->user()->hasPermissions('voucherdelete') || auth()->user()->hasPermissions('voucheredit'))
                                                <td class="fixed">
                                                    @if(auth()->user()->hasPermissions('voucherdelete'))
                                                    <a href="{{ route('voucherdelete',[$voc->id,$voc->category_id]) }}" class="btn btn-danger btn-sm" id="{{ $voc->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
                                                    @endif
                                                    @if(auth()->user()->hasPermissions('voucheredit'))
                                                    <a href="{{ route('voucheredit',[$voc->id]) }}" class="btn btn-primary btn-sm" id="{{ $voc->id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                    @endif
                                                @endif
                                                </td>
                                                <td class="textdate">{{ $voc->voc_date }}</td>
                                                <td><a 
                                                    @if($voc->voc_file!='')href="{{ asset('storage/'.$voc->voc_file) }}" target="_blank"
                                                    @else
                                                    href="javascript::void(0)"
                                                    @endif
                                                    >{{ $voc->voc_no }}</a>
                                                </td>
                                                <td>{{ $voc->voc_whom }}</td>
                                                <td>{{ $voc->voc_acc }}</td>
                                                <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_cash) }}</td>
                                                <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_bank) }}</td>
                                                {{-- categories --}}
                                                @foreach($voc_json as $key=>$json)
                                                    @if($json!='')
                                                        <td class="text-end table-secondary {{ $aslib[$key] }}">{{ Helper::numberFormat($json) }}</td>
                                                    @else
                                                        <td class="text-end table-secondary {{ $aslib[$key] }}">0.00</td>
                                                    @endif
                                                @endforeach
                                                @if(request('id')==37)
                                                <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_memo_stk) }}</td>
                                                @endif
                                                <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_property) }}</td>
                                                <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_fd) }}</td>
                                                {{-- properties --}}
                                                {{-- <td>
                                                    @if(auth()->user()->hasPermissions('crv'))
                                                    <a href="{{ route('crv',[$voc->id,$voc->category_id,$voc->voc_no]) }}" class="btn btn-primary btn-sm">CRV</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermissions('niv'))
                                                    <a href="{{ route('niv',[$voc->id,$voc->category_id,$voc->voc_no]) }}" class="btn btn-primary btn-sm">NIV</a>
                                                    @endif
                                                </td> --}}
                                            </tr>
                                            @else
                                            @endif
                                            @endforeach
                                            @php
                                            // print_r($cattotal); this gives blank array that my getting fields are missing for sum of array values resolve this for array_walk
                                            if(count($vouchers)>0){
                                                array_walk($bbf['bfftotal'], function(&$value, $key) use ($cattotal) {
                                                    $value += $cattotal[str_replace(" ","_",$key)]; // Add the values from array2 to array1, keeping the key
                                                });
                                            }
                                                // print_r($bbf['bfftotal']);
                                            @endphp
                                            <tr>
                                                @php $col=(auth()->user()->hasPermissions('voucherdelete') || auth()->user()->hasPermissions('voucheredit'))?5:4; @endphp
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right"></td>
                                                <td class="text-end table-primary messtotal liabilities">0.00</td>
                                                <td class="text-end table-primary messtotal liabilities">0.00</td>
                                                @foreach($bbf['bfftotal'] as $key=>$c)
                                                @if($key=='sy_dr')
                                                    <td class="text-end table-primary messtotal {{$aslib[str_replace(" ","_",$key)]}} ">{{ Helper::numberFormat($messbilltotaldr) }}</td>
                                                @else
                                                    <td class="text-end table-primary messtotal {{$aslib[str_replace(" ","_",$key)]}} ">0.00</td>
                                                @endif
                                                @endforeach
                                                @if(request('id')==37)
                                                <td class="text-end table-primary messtotal liabilities">0.00</td>
                                                @endif
                                                <td class="text-end table-primary messtotal assets">0.00</td>
                                            </tr>
                                            <tr>
                                                @php $col=(auth()->user()->hasPermissions('voucherdelete') || auth()->user()->hasPermissions('voucheredit'))?5:4; @endphp
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right"><b>Total</b></td>
                                                <td class="text-end table-primary total liabilities">{{ Helper::numberFormat($cash+$voc_cash) }}</td>
                                                <td class="text-end table-primary total liabilities">{{ Helper::numberFormat($bank+$voc_bank) }}</td>
                                                @foreach($bbf['bfftotal'] as $key=>$c)
                                                    <td class="text-end table-primary total {{$aslib[str_replace(" ","_",$key)]}} ">{{Helper::numberFormat($c)}}</td>
                                                @endforeach
                                                @if(request('id')==37)
                                                <td class="text-end table-primary total liabilities">{{ Helper::numberFormat($memostk+$voc_memo_stk) }}</td>
                                                @endif
                                                <td class="text-end table-primary total assets">{{ Helper::numberFormat($property+$voc_property) }}</td>
                                                <td class="text-end table-primary total assets">{{ Helper::numberFormat($fd+$voc_fd) }}</td>
                                                <td id="toatlamt" class="text-end table-primary fw-bold" style="font-weight:bold"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right"><b>Balance</b></td>
                                                <td class="text-end table-warning text-black balance liabilities">{{ isset($bbf['voc_cash'])?Helper::numberFormat($bbf['voc_cash']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end table-warning text-black balance liabilities">{{ isset($bbf['voc_bank'])?Helper::numberFormat($bbf['voc_bank']):Helper::numberFormat(0) }}</td>
                                                @if(isset($bbf['bfftotal']))
                                                @foreach($bbf['bfftotal'] as $key=>$value)
                                                    <td class="text-end table-warning text-black balance {{$aslib[str_replace(" ","_",$key)]}}">{{Helper::numberFormat($value??0)}}</td>
                                                @endforeach
                                                @endif
                                                @if(request('id')==37)
                                                <td class="text-end table-warning text-black balance liabilities">{{ isset($bbf['voc_memo_stk'])?Helper::numberFormat($bbf['voc_memo_stk']):Helper::numberFormat(0) }}</td>
                                                @endif
                                                <td class="text-end table-warning text-black balance assets">{{ isset($bbf['voc_property'])?Helper::numberFormat($bbf['voc_property']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end table-warning text-black balance assets">{{ isset($bbf['voc_fd'])?Helper::numberFormat($bbf['voc_fd']):Helper::numberFormat(0) }}</td>
                                                <td id="balanceamt" class="text-end table-warning text-black fw-bold" style="font-weight:bold"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right"><b>G/Total</b></td>
                                                <td class="text-end grand liabilities">{{ isset($fromreceipt['voc_cash'])?Helper::numberFormat($fromreceipt['voc_cash']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end grand liabilities">{{ isset($fromreceipt['voc_bank'])?Helper::numberFormat($fromreceipt['voc_bank']):Helper::numberFormat(0) }}</td>
                                                @foreach($fromreceipt['credit'] as $key=>$value)
                                                    <td class="text-end grand {{$aslib[$key]}}">{{Helper::numberFormat($value)}}</td>
                                                @endforeach
                                                @if(request('id')==37)
                                                <td class="text-end grand liabilities">{{ isset($fromreceipt['voc_memo_stk'])?Helper::numberFormat($fromreceipt['voc_memo_stk']):Helper::numberFormat(0) }}</td>
                                                @endif
                                                <td class="text-end grand assets">{{ isset($fromreceipt['voc_property'])?Helper::numberFormat($fromreceipt['voc_property']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end grand assets">{{ isset($fromreceipt['voc_fd'])?Helper::numberFormat($fromreceipt['voc_fd']):Helper::numberFormat(0) }}</td>
                                                <td id="grandamt" class="text-end fw-bold" style="font-weight:bold"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    {{-- <div class="col-2">
                                        <table id="table2" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">Assets</th>
                                            </thead>
                                            <tbody>
                                                <tr><td>Cash In Hand</td><td class="text-end">{{ $cash }}</td></tr>
                                                <tr><td>Cash In Bank</td><td class="text-end">{{ $bank }}</td></tr>
                                                @php $assets = $liabilities = 0 @endphp
                                                @foreach ($subcategories as $sub)
                                                    @if(count($tables)>0 && $sub->type == 'Assets')
                                                    @php  $assets+=$tables[strtolower($sub->name)];@endphp
                                                        <tr>
                                                            <td>{{$sub->name}}</td><td class="text-end">{{ $tables[strtolower($sub->name)]??0 }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr><td><b>Total</b></td><td class="text-end"><b>{{ $assets+$cash+$bank }}</b></td></tr>
                                                <tr><td><b>Property</b></td><td class="text-end"><b>0.00</b></td></tr>
                                                <tr><td><b>Grand</b></td><td class="text-end"><b>0.00</b></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-2">
                                        <table id="table3" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">Liabilities</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($subcategories as $sub)
                                                    @if(count($tables)>0 && $sub->type == 'Liabilities')
                                                    @php  $liabilities+=$tables[strtolower($sub->name)];@endphp
                                                        <tr>
                                                            <td>{{$sub->name}}</td><td class="text-end">{{ $tables[strtolower($sub->name)]??0 }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr><td><b>Total</b></td><td class="text-end"><b>{{ $liabilities }}</b></td></tr>
                                                <tr><td><b>Property</b></td><td class="text-end"><b>0.00</b></td></tr>
                                                <tr><td><b>Grand</b></td><td class="text-end"><b>0.00</b></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-2">
                                        <table id="table4" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">Bank Reconciliation</th>
                                            </thead>
                                            <tbody>
                                                <tr><td><b>Cash in bank as per Acc. Book</b></td><td class="text-end"><b>0.00</b></td></tr>
                                                <tr><td><b>Cash in bank as per statement</b></td><td class="text-end"><b>0.00</b></td></tr>
                                                <tr><td><b>Difference</b></td><td class="text-end"><b>Nil</b></td></tr>
                                            </tbody>
                                        </table>                                        
                                    </div> --}}
                                    <table id="date-range-table" style="width:100%; border-collapse:collapse;" class="d-none" border="1">
                                        <tr>
                                            <td colspan="8" style="text-align:center;font-size:18px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                Date Range: <span id="date-range">{{ $start }} To {{ $end }}</span>
                                                @if($search!='')
                                                    / <span style="margin-left:10px">Data Based On Search Keyword : {{ $search }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div><!-- end table responsive -->
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
<div class="modal fade" id="addvoucher" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<style>
    legend{font-size: 20px;}
        .small-input {
        -moz-appearance: textfield;
    }
        .small-input::-webkit-inner-spin-button {
        display: none;
    }
    .small-input::-webkit-outer-spin-button,
    .small-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Voucher Detail</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="voucher-form" action="{{route('voucheradd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $fundfor }}" name="voc_fund_type">
                <input type="hidden" value="{{ $cat_id }}" name="cat_id">
                <input type="hidden" value="Payment" name="voc_type">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="voc_date" class="form-label">Date No</label>
                            <input type="date" name="voc_date" class="form-control" id="voc_date" value={{ $start }}>
                            <span class="text-danger" id="voc_date_error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="voc_no" class="form-label">Voucher No</label>
                            <input type="text" name="voc_no" class="form-control" id="voc_no" placeholder="Enter VR No.">
                            <span class="text-danger" id="voc_no_error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="voc_file" class="form-label">Upload Voucher</label>
                            <input type="file" name="voc_file" class="form-control" id="voc_file" placeholder="Upload Voucher">
                            <span class="text-danger" id="voc_file_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_whom" class="form-label">From Whom</label>
                            <input type="text" name="voc_whom" class="form-control" id="voc_whom" placeholder="Enter From">
                            <span class="text-danger" id="voc_whom_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_acc" class="form-label">What Account</label>
                            <input type="text" name="voc_acc" class="form-control" id="voc_acc" placeholder="Enter Account">
                            <span class="text-danger" id="voc_acc_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_cash" class="form-label">Cash(Rs.)</label>
                            <input type="number" step="0.01" min="0" name="voc_cash" class="form-control" id="voc_cash" placeholder="Cash Amount" value="0">
                            <span class="text-danger" id="voc_cash_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_bank" class="form-label">Bank(Rs.)</label>
                            <input type="number" step="0.01" min="0" name="voc_bank" class="form-control" id="voc_bank" placeholder="Bank Amount" value="0">
                            <span class="text-danger" id="voc_bank_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="border rounded-3 p-3">
                            <legend class="float-none w-auto px-3" >Credit to Ledger Accounts</legend>
                            <div class="row">
                                @foreach ($subcategories as $subcats)
                                    @php $name = strtolower(preg_replace('/\s+/', '_', trim($subcats->name)));@endphp
                                    <div class="col-md-4 mb-3">
                                        <input type="number" step="0.01" min="0" name="{{ $name }}" class="form-control subcats small-input" id="bank" placeholder="{{ $subcats->name }}">
                                    </div>                                    
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    @if(request('id')==37)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_memo_stk" class="form-label">Memento Stock</label>
                            <input type="number" step="0.01" min="0" name="voc_memo_stk" class="form-control" id="voc_memo_stk" placeholder="Enter Amount">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_property" class="form-label">Property</label>
                            <input type="number" step="0.01" min="0" name="voc_property" class="form-control" id="voc_property" placeholder="Enter Amount">
                        </div>
                    </div>                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_fd" class="form-label">FD</label>
                            <input type="number" min="0" name="voc_fd" class="form-control" id="voc_fd" placeholder="Enter Amount">
                        </div>
                    </div>                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="button">Submit Voucher</button>
                    {{-- <button class="btn btn-primary w-100" id="submitBtnSpin"><i class="las la-spinner"></i></button> --}}
                </div>
            </form>
            <div id="loadingSpinner" class="loader" style="display:none;"></div> 
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->

<!-- Modal -->
<div class="modal fade" id="bbfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <style>
        legend{font-size: 20px;}
        .small-input {
        -moz-appearance: textfield;
    }
        .small-input::-webkit-inner-spin-button {
        display: none;
    }
    .small-input::-webkit-outer-spin-button,
    .small-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    </style>
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Debit BBF Detail</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="bbf-form" action="{{route('bbfadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $fundfor }}" name="voc_fund_type">
                <input type="hidden" value="{{ $cat_id }}" name="cat_id" id="bbf_cat_id">
                <input type="hidden" value="Payment" name="voc_type">
                <input type="hidden" value="{{ $start }}" name="start">
                <input type="hidden" value="{{ $end }}" name="end">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_cash" class="form-label">Cash(Rs.)</label>
                            <input type="number" min="0" step="0.01" name="voc_cash" class="form-control" id="voc_cash" placeholder="Cash Amount" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_bank" class="form-label">Bank(Rs.)</label>
                            <input type="number" min="0" step="0.01" name="voc_bank" class="form-control" id="voc_bank" placeholder="Bank Amount" value="0">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="border rounded-3 p-3">
                            <legend class="float-none w-auto px-3" >BBF Detail</legend>
                            <div class="row">
                                @foreach ($subcategories as $subcats)
                                    @php $name = strtolower(preg_replace('/\s+/', '_', trim($subcats->name)));@endphp
                                    <div class="col-md-4 mb-3">
                                        <input type="number" min="0" step="0.01" name="{{ $name }}" class="form-control small-input" id="bank" placeholder="{{ $subcats->name }}">
                                    </div>                                    
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                    @if(request('id')==37)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_memo_stk" class="form-label">Memento Stock</label>
                            <input type="number" step="0.01" min="0" name="voc_memo_stk" class="form-control" id="voc_memo_stk" placeholder="Enter Amount">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_property" class="form-label">Property</label>
                            <input type="number" step="0.01" min="0" name="voc_property" class="form-control" id="voc_property" placeholder="Enter Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_fd" class="form-label">FD</label>
                            <input type="number" step="0.01" min="0" name="voc_fd" class="form-control" id="voc_fd" placeholder="Enter Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_date" class="form-label">BBF Date</label>
                            <input type="date" name="voc_date" class="form-control" id="voc_date" value="{{ $start }}" required>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" type="submit">Submit BBF</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<script>
$(document).ready(function() {
    // Auto BBF functionality
    $('#autoBbfBtn').on('click', function(e) {
        e.preventDefault();
        
        // Change form action to Auto BBF route FIRST
        $('#bbf-form').attr('action', '{{ route("autobbfadd") }}');
        
        // Remove existing source_page field and add new one
        $('#bbf-form input[name="source_page"]').remove();
        $('#bbf-form').append('<input type="hidden" name="source_page" value="voucherp">');
        
        // Add original category ID to process subcategories correctly
        $('#bbf-form input[name="original_cat_id"]').remove();
        $('#bbf-form').append('<input type="hidden" name="original_cat_id" value="{{ $cat_id }}">');
        
        // Auto BBF: Stay in same category, switch to Receipt side
        $('#bbf_cat_id').val('{{ $cat_id }}');
        
        // Change voc_type to Receipt (opposite of Payment)
        $('#bbf-form input[name="voc_type"]').val('Receipt');
        
        // Get balance values directly from the DOM (Balance row)
        const balanceCells = $('.balance');
        let cashTotal = 0;
        let bankTotal = 0;
        let propertyTotal = 0;
        let fdTotal = 0;
        let memoTotal = 0;
        
        console.log('Total balance cells found:', balanceCells.length);
        
        // Debug: log all balance values
        balanceCells.each(function(index) {
            console.log('Cell', index, ':', $(this).text());
        });
        
        // Extract cash and bank totals (first two balance cells)
        if (balanceCells.length >= 2) {
            const cashText = balanceCells.eq(0).text().trim().replace(/[,\s]/g, '');
            const bankText = balanceCells.eq(1).text().trim().replace(/[,\s]/g, '');
            cashTotal = parseFloat(cashText) || 0;
            bankTotal = parseFloat(bankText) || 0;
            console.log('Cash text:', cashText, 'Parsed:', cashTotal);
            console.log('Bank text:', bankText, 'Parsed:', bankTotal);
        }
        
        // Extract FD total (last balance cell)
        if (balanceCells.length > 0) {
            const fdText = balanceCells.eq(-1).text().trim().replace(/[,\s]/g, '');
            fdTotal = parseFloat(fdText) || 0;
            console.log('FD text:', fdText, 'Parsed:', fdTotal);
        }
        
        // Extract property total (second to last balance cell)
        if (balanceCells.length > 1) {
            const propertyText = balanceCells.eq(-2).text().trim().replace(/[,\s]/g, '');
            propertyTotal = parseFloat(propertyText) || 0;
            console.log('Property text:', propertyText, 'Parsed:', propertyTotal);
        }
        
        // Extract memo stock total if exists (third to last for category 37)
        @if(request('id')==37)
        if (balanceCells.length > 2) {
            const memoText = balanceCells.eq(-3).text().trim().replace(/[,\s]/g, '');
            memoTotal = parseFloat(memoText) || 0;
            console.log('Memo text:', memoText, 'Parsed:', memoTotal);
        }
        @endif
        
        // Populate basic fields with extracted totals
        console.log('Setting cash input to:', cashTotal);
        $('#bbf-form input[name="voc_cash"]').val(cashTotal);
        console.log('Setting bank input to:', bankTotal);
        $('#bbf-form input[name="voc_bank"]').val(bankTotal);
        console.log('Setting property input to:', propertyTotal);
        $('#bbf-form input[name="voc_property"]').val(propertyTotal);
        console.log('Setting FD input to:', fdTotal);
        $('#bbf-form input[name="voc_fd"]').val(fdTotal);
        @if(request('id')==37)
        console.log('Setting memo input to:', memoTotal);
        $('#bbf-form input[name="voc_memo_stk"]').val(memoTotal);
        @endif
        
        // Get subcategory totals from the balance cells (excluding cash, bank, memo, property, fd)
        const subcategoryStartIndex = 2; // After cash and bank
        let subcategoryEndIndex = balanceCells.length - 2; // Before property and FD
        @if(request('id')==37)
        subcategoryEndIndex = balanceCells.length - 3; // Before memo, property and FD
        @endif
        
        // Get all subcategory input names and populate them
        const subcategoryInputs = $('#bbf-form input[type="number"]').not('input[name="voc_cash"], input[name="voc_bank"], input[name="voc_property"], input[name="voc_fd"], input[name="voc_memo_stk"]');
        console.log('Found subcategory inputs:', subcategoryInputs.length);
        subcategoryInputs.each(function(index) {
            const cellIndex = subcategoryStartIndex + index;
            if (cellIndex < subcategoryEndIndex && balanceCells.length > cellIndex) {
                const cellText = balanceCells.eq(cellIndex).text().trim().replace(/[,\s]/g, '');
                const value = parseFloat(cellText) || 0;
                const inputName = $(this).attr('name');
                console.log('Setting subcategory input', inputName, 'to value:', value);
                $(this).val(value);
            }
        });
        
        // Change modal title to indicate auto-population
        $('.modal-title').text('Auto BBF - Debit to Credit (Same Category)');
        
        toastr.success('BBF form populated for same category transfer!');
    });
    
    // Reset modal title when regular Add BBF is clicked
    $('#addBbfBtn').on('click', function() {
        $('.modal-title').text('Enter Debit BBF Detail');
        $('#bbf-form').attr('action', '{{ route("bbfadd") }}'); // Reset to regular BBF route
        $('#bbf_cat_id').val('{{ $cat_id }}'); // Reset to original cat_id
        $('#bbf-form input[name="voc_type"]').val('Payment'); // Reset to Payment for credit page
        $('#bbf-form input[name="source_page"]').remove(); // Remove source_page field
        $('#bbf-form input[name="original_cat_id"]').remove(); // Remove original_cat_id field
        $('#bbf-form')[0].reset();
    });

    // $('#submitBtnSpin').hide();
    $('#voucher-form').on('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        $('#loadingSpinner').show();
        $('#submitBtn').attr('disabled',true);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success=='200'){
                    $('#voucher-form')[0].reset();
                    $('#addvoucher').modal('hide');
                    $('.text-danger').text('');
                    $('.form-control').css('border', '');
                    //toastr.success(response.message);
                    location. reload();
                }else{
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#loadingSpinner').hide();
                $('#submitBtn').attr('disabled',false);
                let errors = xhr.responseJSON.errors;
                $('.text-danger').text('');
                $('.form-control').css('border', '');
                $.each(errors, function(key, value) {
                    $('#'+key+'_error').text(errors[key])
                    $('#'+key).css('border','1px solid red')
                });
            }
        });
    });
});

//For Checking Categroies Divided Sum Not Greater Than Cach+Bank
$(document).ready(function() {
    $('#submitBtn').click(function() {
        // Get values of cash and bank
        // var cash = parseFloat($('#voc_cash').val()) || 0;
        // var bank = parseFloat($('#voc_bank').val()) || 0;
        // var cashBankTotal = cash + bank;

        // Calculate the total of column amounts
        // var columnTotal = 0;
        // $('.subcats').each(function() {
        //     columnTotal += parseFloat($(this).val()) || 0;
        // });

        // Check if column total matches cash + bank total
        // if (columnTotal > cashBankTotal) {
        //     toastr.error(`The sum of columns ${columnTotal} > ${cashBankTotal} the sum of cash and bank .`);
        // } else {
        //     $('#voucher-form').submit();
        // }
        $('#voucher-form').submit();
    });
});

$(document).ready(function() {
    // $('.balance').each(function(index) {
    //     var totalValue = parseFloat($('.total').eq(index).text()) || 0;
    //     var grandValue = parseFloat($('.grand').eq(index).text()) || 0;
    //     var balanceValue = grandValue - totalValue;
    //     $(this).text(balanceValue.toFixed(2));
    // });

    // Calculate balance for 'lib' category
  $('.liabilities').each(function(index) {
    // Get the total and grand values for this 'lib' row
    var totalValue = parseFloat($('.liabilities.total').eq(index).text()) || 0;
    var grandValue = parseFloat($('.liabilities.grand').eq(index).text()) || 0;
    
    // Calculate balance (grandValue - totalValue)
    var balanceValue = grandValue - totalValue;
    
    // Set the balance in the corresponding cell
    $('.liabilities.balance').eq(index).text(balanceValue.toFixed(2));
  });

  // Calculate balance for 'assets' category
  $('.assets').each(function(index) {
    // Get the total and grand values for this 'assets' row
    var totalValue = parseFloat($('.assets.total').eq(index).text()) || 0;
    var messtotalValue = parseFloat($('.assets.messtotal').eq(index).text()) || 0;
    // var grandValue = parseFloat($('.assets.grand').eq(index).text()) || 0;
    var total = totalValue+messtotalValue
    // Calculate balance (grandValue - totalValue)
    // var balanceValue = totalValue;
    var zero = 0.00;
    // Set the balance in the corresponding cell
    $('.assets.total').eq(index).text(total.toFixed(2));
    $('.assets.grand').eq(index).text(total.toFixed(2));
    $('.assets.balance').eq(index).text(zero.toFixed(2));
  });
});

$(document).ready(function() {
    //Get Grand Total From Total-Balance
    // $('td.grand').each(function(index) {
    //     var total = parseFloat($('td.total').eq(index).text());
    //     var balance = parseFloat($('td.balance').eq(index).text());
    //     // console.log(total,balance);
    //     var grand = total - balance;
    //     $(this).text(grand);
    // });
    
    //Total Balance Grand Below CRV/NIV
    var total=balance=grand=0;
    $('.total').each(function() {
        total+=parseFloat($(this).text())||0;
    });
    $('.balance').each(function() {
        balance+=parseFloat($(this).text())||0;
    });
    $('.grand').each(function() {
        grand+=parseFloat($(this).text())||0;
    });

    // var cash_total = parseFloat($('#cash_total').text());
    // var bank_total = parseFloat($('#bank_total').text());
    // var sum = total + cash_total + bank_total;
    // var cash_balance = parseFloat($('#cash_balance').text());
    // var bank_balance = parseFloat($('#bank_balance').text());
    // var bal = balance + cash_balance + bank_balance;
    // var cash_grand = parseFloat($('#cash_grand').text());
    // var bank_grand = parseFloat($('#bank_grand').text());
    // var grd = grand + cash_grand + bank_grand;
    $('#toatlamt').text(total.toFixed(2))
    $('#balanceamt').text(balance.toFixed(2))
    $('#grandamt').text(grand.toFixed(2))    
});


</script>

<script src="{{ asset('assets/js/newexcelexport.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#btnExport").click(function() {
            start = $(this).attr('start-date');
            end = $(this).attr('end-date');
            month = $(this).attr('month');
            fundtype = $(this).attr('fundtype');
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
            a.download = `${fundtype}-payments-${start}-${end}.xlsx`;
            a.click();
            URL.revokeObjectURL(url);
        });
      
    });
</script>
@endsection
