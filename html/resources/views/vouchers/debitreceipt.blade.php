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

.btn {
    margin-left: 10px; /* Space between buttons */
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
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{ count($vouchers)>0?count($vouchers)-1:0 }}" target="addvoucher" currentmonth="Receipt (Credit) MONTH OF {{ date('M, Y',strtotime($start)) }}"/>
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
                <div class="col-md-12">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ route('chequelist',[request()->route()->parameters['id'],$start,$end]) }}" class="btn btn-primary btn-sm me-1">Cheque</a>
                        <a href="{{ route('sycrlist',[request()->route()->parameters['id'],$start,$end]) }}" class="btn btn-primary btn-sm me-1">Sy Cr</a>
                        <a href="{{ route('sydrlist',[request()->route()->parameters['id'],$start,$end]) }}" class="btn btn-primary btn-sm me-1">Sy Dr</a>
                        <a href="{{ route('propertieslist',[request()->route()->parameters['id'],$start,$end]) }}" class="btn btn-primary btn-sm me-1">Property</a>
                        <a href="{{ route('stmtlist',[request()->route()->parameters['id'],$start,$end]) }}" class="btn btn-primary btn-sm me-1">Bank Statement</a>
                      </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-xs-6">
                    <form action="{{ route('voucher',[request()->route()->parameters['id']]) }}">
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
                                <a href="{{ route('voucher',[request()->route()->parameters['id']]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                    <i class="las la-redo-alt"></i>
                                </a>                        
                        </div>                      
                    </form>
                    <button id="btnExport" class="btn btn-sm btn-primary float-end mt-4" start-date="{{ $start }}" end-date="{{ $end }}" month="Receipt (Credit) MONTH OF {{ now()->format('M, Y') }}" fundtype="{{ $fundfor }}"><i class="me-1 las la-file-export"></i>Export</button>              
                    <button id="addBbfBtn" class="btn btn-primary btn-sm float-end mt-4" data-bs-toggle="modal" data-bs-target="#bbfmodal"><i class="las la-plus me-1"></i>Add BBF</button>
                    <button id="autoBbfBtn" class="btn btn-success btn-sm float-end mt-4 me-2" data-bs-toggle="modal" data-bs-target="#bbfmodal"><i class="las la-magic me-1"></i>Auto BBF Debit</button>
                    @if(isset($categoryInfo) && $categoryInfo->has_total_allotment)
                    <button class="btn btn-primary btn-sm float-end mt-4 me-2" data-bs-toggle="modal" data-bs-target="#totalallotmentmodal"><i class="las la-calculator me-1"></i>Total Allotment</button>
                    @endif
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card" id="tables-container">
                                <div id="tbs">
                                    <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="vtable" border="1">
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
                                                <td></td>
                                                <td>{{$bbf['voc_date']}}</td>
                                                <td colspan="3" class="text-center">bbf</td>
                                                <td class="text-end bbftotal">{{ isset($bbf['voc_cash'])?Helper::numberFormat($bbf['voc_cash']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end bbftotal">{{ isset($bbf['voc_bank'])?Helper::numberFormat($bbf['voc_bank']):Helper::numberFormat(0) }}</td>
                                                @if(isset($bbf['bfftotal']))
                                                @foreach($bbf['bfftotal'] as $key=>$value)
                                                    <td class="text-end bbftotal">{{Helper::numberFormat($value??0)}}</td>
                                                @endforeach
                                                @endif
                                                @if(request('id')==37)
                                                <td class="text-end bbftotal">{{ isset($bbf['voc_memo_stk'])?Helper::numberFormat($bbf['voc_memo_stk']):Helper::numberFormat(0) }}</td>
                                                @endif
                                                <td class="text-end bbftotal">{{ isset($bbf['voc_property'])?Helper::numberFormat($bbf['voc_property']):Helper::numberFormat(0) }}</td>
                                                <td class="text-end bbftotal">{{ isset($bbf['voc_fd'])?Helper::numberFormat($bbf['voc_fd']):Helper::numberFormat(0) }}</td>
                                            </tr>
                                            @php
                                                $cash=$bank=$memostk=$property=$fd=$v=0;
                                                $cattotal = []; $tables = [];$tab=0;
                                            @endphp
                                            @foreach($vouchers as $voc)
                                                @php
                                                    $cash += $voc->voc_cash;
                                                    $bank += $voc->voc_bank;
                                                    $memostk += $voc->voc_memo_stk;
                                                    $property += $voc->voc_property;
                                                    $fd += $voc->voc_fd;
                                                    $voc_json =  \App\Helpers\Helper::categories_voc_json($voc->voc_json,$cat_id);
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
                                                        >{{ $voc->voc_no }}</a> </td>
                                                    <td>{{ $voc->voc_whom }}</td>
                                                    <td>{{ $voc->voc_acc }}</td>
                                                    <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_cash) }}</td>
                                                    <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_bank) }}</td>
                                                    {{-- categories --}}
                                                    @foreach($voc_json as $json)
                                                        @if($json!='')
                                                            <td class="text-end table-secondary">{{ Helper::numberFormat($json) }}</td>
                                                        @else
                                                            <td class="text-end table-secondary">{{ Helper::numberFormat(0) }}</td>
                                                        @endif
                                                    @endforeach
                                                    {{-- properties --}}
                                                    {{-- <td>
                                                        @if(auth()->user()->hasPermissions('crv'))
                                                        <a href="{{ route('crv',[$voc->id,$voc->category_id,$voc->voc_no]) }}" class="btn btn-primary btn-sm">CRV<a>
                                                        @endif
                                                        @if(auth()->user()->hasPermissions('niv'))
                                                        <a href="{{ route('niv',[$voc->id,$voc->category_id,$voc->voc_no]) }}" class="btn btn-primary btn-sm">NIV<a>
                                                        @endif
                                                    </td> --}}
                                                    @if(request('id')==37)
                                                    <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_memo_stk??0) }}</td>
                                                    @endif
                                                    <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_property??0) }}</td>
                                                    <td class="text-end table-secondary">{{ Helper::numberFormat($voc->voc_fd??0) }}</td>
                                                </tr>
                                                @else

                                                @endif
                                            @endforeach
                                            @php $col=(auth()->user()->hasPermissions('voucherdelete') || auth()->user()->hasPermissions('voucheredit'))?5:4; @endphp
                                            {{-- Messbill --}}
                                            @if(request('id')==63 || request('id')==37)
                                            <tr>
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right">Messbill</td>
                                                <td class="text-end messtotal">0.00</td>
                                                <td class="text-end messtotal">0.00</td>
                                                @if(isset($messbill))
                                                @foreach($messbill->voc_json as $key=>$value)
                                                    <td class="text-end messtotal">{{Helper::numberFormat($value??0)}}</td>
                                                @endforeach
                                                @endif
                                                @if(request('id')==37)
                                                <td class="text-end messtotal">0.00</td>
                                                @endif
                                                <td class="text-end messtotal">0.00</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right">Total</td>
                                                <td class="text-end table-primary total">{{ Helper::numberFormat($cash) }}</td>
                                                <td class="text-end table-primary total">{{ Helper::numberFormat($bank) }}</td>
                                                    @foreach($cattotal as $c)
                                                        <td class="text-end table-primary total">{{ Helper::numberFormat($c??0) }}</td>
                                                    @endforeach
                                                    @if(request('id')==37)
                                                    <td class="text-end table-primary total">{{ Helper::numberFormat($memostk) }}</td>
                                                    @endif
                                                    <td class="text-end table-primary total">{{ Helper::numberFormat($property) }}</td>
                                                    <td class="text-end table-primary total">{{ Helper::numberFormat($fd) }}</td>
                                                    <td id="toatlamt" class="text-end table-primary fw-bold" style="font-weight:bold"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right">Balance</td>
                                                <td class="text-end table-warning text-black balance">{{ isset($bbf['voc_cash'])?Helper::numberFormat($bbf['voc_cash']):Helper::numberFormat(0) }}</td>
                                                    <td class="text-end table-warning text-black balance">{{ isset($bbf['voc_bank'])?Helper::numberFormat($bbf['voc_bank']):Helper::numberFormat(0) }}</td>
                                                    @if(isset($bbf['bfftotal']))
                                                    @foreach($bbf['bfftotal'] as $key=>$value)
                                                        <td class="text-end table-warning text-black balance">{{Helper::numberFormat($value??0)}}</td>
                                                    @endforeach
                                                    @endif
                                                    @if(request('id')==37)
                                                    <td class="text-end table-warning text-black balance">{{ isset($bbf['voc_memo_stk'])?Helper::numberFormat($bbf['voc_memo_stk']):Helper::numberFormat(0) }}</td>
                                                    @endif
                                                    <td class="text-end table-warning text-black balance">{{ isset($bbf['voc_property'])?Helper::numberFormat($bbf['voc_property']):Helper::numberFormat(0) }}</td>
                                                    <td class="text-end table-warning text-black balance">{{ isset($bbf['voc_fd'])?Helper::numberFormat($bbf['voc_fd']):Helper::numberFormat(0) }}</td>
                                                    <td id="balanceamt" class="text-end table-warning text-black fw-bold" style="font-weight:bold"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $col }}" class="text-end" style="text-align:right">G/Total</td>
                                                {{-- <td class="text-end grand">{{ $cash }}</td>
                                                    <td class="text-end grand">{{ $bank }}</td>
                                                    @foreach($cattotal as $c)
                                                        <td class="text-end grand ">{{ $c??0 }}</td>
                                                    @endforeach
                                                    <td class="text-end grand">{{ $memostk }}</td>
                                                    <td class="text-end grand">{{ $property }}</td> --}}

                                                    <td class="text-end paymenttotal ">{{ isset($paymentTotal['voc_cash'])?Helper::numberFormat($paymentTotal['voc_cash']):Helper::numberFormat(0) }}</td>
                                                    <td class="text-end paymenttotal ">{{ isset($paymentTotal['voc_bank'])?Helper::numberFormat($paymentTotal['voc_bank']):Helper::numberFormat(0) }}</td>
                                                    @if(isset($paymentTotal['paymenttotal']))
                                                        @foreach($paymentTotal['paymenttotal'] as $key=>$value)
                                                            <td class="text-end paymenttotal ">{{Helper::numberFormat($value)}}</td>
                                                        @endforeach
                                                    @endif
                                                    @if(request('id')==37)
                                                    <td class="text-end paymenttotal ">{{ isset($paymentTotal['voc_memo_stk'])?Helper::numberFormat($paymentTotal['voc_memo_stk']):Helper::numberFormat(0) }}</td>
                                                    @endif
                                                    <td class="text-end paymenttotal ">{{ isset($paymentTotal['voc_property'])?Helper::numberFormat($paymentTotal['voc_property']):Helper::numberFormat(0) }}</td>
                                                    <td class="text-end paymenttotal ">{{ isset($paymentTotal['voc_fd'])?Helper::numberFormat($paymentTotal['voc_fd']):Helper::numberFormat(0) }}</td>
                                                    <td id="grandamt" class="text-end fw-bold" style="font-weight:bold"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @php
                                    $date=strtotime($start);
                                    $month=date("M",$date);
                                    $year=date("Y",$date);
                                @endphp
                                <div class="row" >
                                    {{-- All Assets --}}
                                    {{-- <div class="row">
                                        <div class="col-6">
                                            <table id="table1" width="100%" class="table  table-hover table-nowrap align-middle mb-0">
                                                <thead>
                                                    <th colspan="2" style="text-align:center;font-size:18px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">BALANCE SHEET</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div> --}}
                                    <div class="col-3">
                                        <table id="table2" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <th colspan="2" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">Assets</th>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    // print_r($paymentBalance);
                                                    // $bbfsumincasezero = array_sum($paymentBalance);
                                                @endphp
                                                {{-- @if($bbfsumincasezero!=0) --}}
                                                <tr><td>Cash In Hand</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_cash']) }}</td></tr>
                                                <tr><td>Cash In Bank</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_bank']) }}</td></tr>
                                                {{-- @endif --}}
                                                @php $assets = $liabilities = 0 @endphp
                                                @foreach ($subcategories as $sub)
                                                    @if(count($tables)>0 && $sub->type == 'Assets')
                                                    @php  $assets+=$paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))];@endphp
                                                        <tr>
                                                            <td>{{$sub->name}}</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))]) }}</td>
                                                        </tr>
                                                    @else
                                                    @if($sub->type == 'Assets')
                                                    @php  $assets+=$paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))];@endphp
                                                        <tr>
                                                            <td>{{$sub->name}}</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))]) }}</td>
                                                        </tr>
                                                    @endif
                                                    @endif
                                                @endforeach
                                                @php $assetsTotal = $assets+$paymentBalance['voc_cash']+$paymentBalance['voc_bank']; @endphp
                                                <tr><td><b>Total</b></td><td class="text-end"><b>{{ Helper::numberFormat($assetsTotal) }}</b></td></tr>
                                                {{-- <tr><td><b>Property</b></td><td class="text-end"><b>0.00</b></td></tr>
                                                <tr><td><b>Grand</b></td><td class="text-end"><b>0.00</b></td></tr> --}}
                                                @if(request('id')==37)
                                                <tr><td>Memento Stock</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_memo_stk']) }}</td></tr>
                                                @endif
                                                <tr><td>Property</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_property']) }}</td></tr>
                                                <tr><td>FD</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_fd']) }}</td></tr>
                                                {{-- <tr><td><b>G/Total</b></td><td class="text-end"><b>{{ Helper::numberFormat($paymentBalance['voc_memo_stk']+$paymentTotal['voc_property']) }}</b></td></tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- All Assets --}}
                                    {{-- All Liabilities --}}
                                    <div class="col-3">
                                        <table id="table3" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <th colspan="2" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">Liabilities</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($subcategories as $sub)
                                                    @if(count($tables)>0 && $sub->type == 'Liabilities')
                                                    @php  $liabilities+=$paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))];@endphp
                                                        <tr>
                                                            <td>{{$sub->name}}</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))]) }}</td>
                                                        </tr>
                                                    @else
                                                    @if($sub->type == 'Liabilities')
                                                     @php  $liabilities+=$paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))];@endphp
                                                        <tr>
                                                            <td>{{$sub->name}}</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['bfftotal'][strtolower(str_replace(" ","_",$sub->name))]) }}</td>
                                                        </tr>
                                                    @endif
                                                    @endif
                                                @endforeach
                                                <tr><td><b>Total</b></td><td class="text-end"><b>{{ Helper::numberFormat($liabilities) }}</b></td></tr>
                                                {{-- <tr><td><b>Property</b></td><td class="text-end"><b>0.00</b></td></tr>
                                                <tr><td><b>Grand</b></td><td class="text-end"><b>0.00</b></td></tr> --}}
                                                @if(request('id')==37)
                                                <tr><td>Memento Stock</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_memo_stk']) }}</td></tr>
                                                @endif
                                                <tr><td>Property</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_property']) }}</td></tr>
                                                <tr><td>FD</td><td class="text-end">{{ Helper::numberFormat($paymentBalance['voc_fd']) }}</td></tr>
                                                {{-- <tr><td><b>G/Total</b></td><td class="text-end"><b>{{ Helper::numberFormat($paymentBalance['voc_memo_stk']+$paymentTotal['voc_property']) }}</b></td></tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- All Liabilities --}}
                                {{-- Assets/Liabilities Diffrence --}}
                                <div class="row mt-3 mb-3">
                                    <div class="col-3">
                                        <table id="table4" width="100%" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <tr>
                                                <td style="text-align:left;font-weight:bold">Assests G/Total</td>
                                                <td style="text-align:right;font-weight:bold">
                                                    @php $assets_gtotal = $assetsTotal+$paymentBalance['voc_memo_stk']+$paymentBalance['voc_property']+$paymentBalance['voc_fd'];  @endphp
                                                    {{ Helper::numberFormat($assets_gtotal) }}
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="hidden" id="assets" value="{{ Helper::numberFormat($assets_gtotal) }}">
                                    </div>
                                    <div class="col-3">
                                        <table id="table5" width="100%" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <tr>
                                                <td style="text-align:left;font-weight:bold">Liabilities G/Total</td>
                                                <td style="text-align:right;font-weight:bold">
                                                    @php $liabilities_gtotal = $liabilities+$paymentBalance['voc_memo_stk']+$paymentBalance['voc_property']+$paymentBalance['voc_fd'];  @endphp
                                                    {{ Helper::numberFormat($liabilities_gtotal) }}
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="hidden" id="liabilities" value="{{ Helper::numberFormat($liabilities_gtotal) }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <table id="table6" width="100%" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <tr>
                                                <td colspan="2" style="text-align:center;font-weight:bold">
                                                    @php $diffaslib = $assets_gtotal - $liabilities_gtotal;  @endphp
                                                    Difference ( Assets - Liabilities ) = {{ Helper::numberFormat($diffaslib) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{-- Assets/Liabilities Diffrence --}}
                                
                                {{-- All Uncleared Cheques (No Date Filter) --}}
                                @if(count($allUnclearedCheques) > 0)
                                <div class="row mt-3">
                                    <div class="col-8">
                                        <table id="table_cheque_summary" class="table table-bordered table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="6" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        Uncleared Cheques (All)
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:left">Cheque Number</th>
                                                    <th style="text-align:left">Cheque Date</th>
                                                    <th class="text-end">Cheque Amount</th>
                                                    <th style="text-align:left">Detail</th>
                                                    <th style="text-align:center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $chq_no = 1; $chq_total = 0; @endphp
                                                @foreach ($allUnclearedCheques as $cheque)
                                                    @php $chq_total += floatval($cheque->cheque_amount); @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $chq_no++ }}</td>
                                                        <td style="text-align:left">{{ $cheque->cheque_number }}</td>
                                                        <td style="text-align:left">{{ date('d-m-Y', strtotime($cheque->cheque_date)) }}</td>
                                                        <td class="text-end">{{ number_format($cheque->cheque_amount, 2, '.', '') }}</td>
                                                        <td style="text-align:left" class="text-wrap">{{ $cheque->cheque_detail }}</td>
                                                        <td style="text-align:center">
                                                            <form action="{{ route('chequeclear', $cheque->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to mark this cheque as cleared?');">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Cleared">
                                                                    <i class="las la-check"></i> Clear
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr style="background-color:#e9ecef;">
                                                    <td colspan="3" style="text-align:right;font-weight:bold;">Total:</td>
                                                    <td class="text-end" style="font-weight:bold;">{{ number_format($chq_total, 2, '.', '') }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                {{-- End All Uncleared Cheques --}}
                                
                                {{-- All Sy Cr (No Date Filter) --}}
                                @if(count($allSyCrs) > 0)
                                <div class="row mt-3">
                                    <div class="col-8">
                                        <table id="table_sycr_summary" class="table table-bordered table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="5" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        Sy Cr (All)
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:left">Label</th>
                                                    <th style="text-align:left">Date</th>
                                                    <th class="text-end">Amount</th>
                                                    <th style="text-align:center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $sycr_no = 1; $sycr_total = 0; @endphp
                                                @foreach ($allSyCrs as $sycr)
                                                    @php $sycr_total += floatval($sycr->sycr_amount); @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $sycr_no++ }}</td>
                                                        <td style="text-align:left">{{ $sycr->sycr_label }}</td>
                                                        <td style="text-align:left">{{ date('d-m-Y', strtotime($sycr->sycr_date)) }}</td>
                                                        <td class="text-end">{{ number_format($sycr->sycr_amount, 2, '.', '') }}</td>
                                                        <td style="text-align:center">
                                                            <a href="{{ route('sycrdelete', $sycr->id) }}" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this Sy Cr record?');">
                                                                <i class="las la-trash"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr style="background-color:#e9ecef;">
                                                    <td colspan="3" style="text-align:right;font-weight:bold;">Total:</td>
                                                    <td class="text-end" style="font-weight:bold;">{{ number_format($sycr_total, 2, '.', '') }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                {{-- End All Sy Cr --}}
                                
                                {{-- All Sy Dr (No Date Filter) --}}
                                @if(count($allSyDrs) > 0)
                                <div class="row mt-3">
                                    <div class="col-8">
                                        <table id="table_sydr_summary" class="table table-bordered table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="5" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        Sy Dr (All)
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:left">Label</th>
                                                    <th style="text-align:left">Date</th>
                                                    <th class="text-end">Amount</th>
                                                    <th style="text-align:center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $sydr_no = 1; $sydr_total = 0; @endphp
                                                @foreach ($allSyDrs as $sydr)
                                                    @php $sydr_total += floatval($sydr->sydr_amount); @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $sydr_no++ }}</td>
                                                        <td style="text-align:left">{{ $sydr->sydr_label }}</td>
                                                        <td style="text-align:left">{{ date('d-m-Y', strtotime($sydr->sydr_date)) }}</td>
                                                        <td class="text-end">{{ number_format($sydr->sydr_amount, 2, '.', '') }}</td>
                                                        <td style="text-align:center">
                                                            <a href="{{ route('sydrdelete', $sydr->id) }}" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this Sy Dr record?');">
                                                                <i class="las la-trash"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr style="background-color:#e9ecef;">
                                                    <td colspan="3" style="text-align:right;font-weight:bold;">Total:</td>
                                                    <td class="text-end" style="font-weight:bold;">{{ number_format($sydr_total, 2, '.', '') }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                {{-- End All Sy Dr --}}
                                
                                {{-- Bank Reconciliation --}}
                                <div class="row mt-2">
                                        @php
                                            $st=2;$ctotal=$stmttotal=0;
                                        @endphp
                                    <div class="col-6 mb-3">
                                        <table id="table7" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <th colspan="3" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">Bank Reconciliation</th>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:center">Detail</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-align:left">1</td>
                                                    <td style="text-align:center">Cash in bank as per Acct Book</td>
                                                    @php $acc_book = number_format($paymentBalance['voc_bank'],2,'.','') @endphp
                                                    <td class="text-end"><b>{{ $acc_book }}</b></td>
                                                </tr>
                                                <tr>
                                                    {{-- <td style="text-align:left">{{ $st++ }}</td> --}}
                                                    @php 
                                                        // $stmt_amount = is_null($stmt)?0:$stmt->stmt_amount;
                                                        // $stmt_label = is_null($stmt)?'Bank Statement':$stmt->stmt_label;
                                                        //  $stmttotal += $stmt->stmt_amount;
                                                    @endphp
                                                    {{-- <td style="text-align:center" class="text-wrap">{{ $stmt_label }}</td>
                                                    <td class="text-end">{{ number_format($stmt_amount,2,'.','') }}</td> --}}
                                                    @if(count($stmt)>0)
                                                    <tr>
                                                        <td></td>
                                                        <th style="text-align:center">Bank Statements</th>
                                                        <td></td>
                                                    </tr>
                                                    @endif
                                                    @forelse ($stmt as $smt)
                                                    @php $stmttotal += $smt->stmt_amount; @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $st++ }}</td>
                                                        <td style="text-align:center" class="text-wrap">{{ $smt->stmt_label }}</td>
                                                        <td class="text-end">{{ number_format($smt->stmt_amount,2,'.','') }}</td>
                                                    </tr>
                                                    @empty
                                                        {{-- <tr>
                                                            <td style="text-align:left">{{ $st++ }}</td>
                                                            <td style="text-align:center">Cheque</td>
                                                            <td style="text-align:right">0.00</td>
                                                        </tr> --}}
                                                    @endforelse
                                                    <td></td>
                                                    <td style="text-align:center;"><b>Sum of Bank Statements</b></td>
                                                    <td class="text-end"><b>{{ number_format($stmttotal,2,'.','') }}</b></td>
                                                </tr>
                                                @if(count($cheques)>0)
                                                <tr>
                                                    <td></td>
                                                    <th style="text-align:center">Cheques Detail</th>
                                                    <td></td>
                                                </tr>
                                                @endif
                                                @forelse ($cheques as $cheque)
                                                    <tr>
                                                        @php $ctotal += $cheque->cheque_amount; @endphp
                                                        <td style="text-align:left">{{ $st++ }}</td>
                                                        <td style="text-align:center" class="text-wrap">{{ $cheque->cheque_detail }}</td>
                                                        <td class="text-end">{{ number_format($cheque->cheque_amount,2,'.','') }}</td>
                                                    </tr>
                                                @empty
                                                    {{-- <tr>
                                                        <td style="text-align:left">{{ $st++ }}</td>
                                                        <td style="text-align:center">Cheque</td>
                                                        <td style="text-align:right">0.00</td>
                                                    </tr> --}}
                                                @endforelse
                                                <td></td>
                                                <td style="text-align:center"><b>Sum of Cheques</b></td>
                                                <td class="text-end"><b>{{ number_format($ctotal,2,'.','') }}</b></td>
                                                <tr>
                                                    @php $finalstmt = $stmttotal-$ctotal; @endphp
                                                    <td></td>
                                                    <td style="text-align:center"><b>Total ( Sum of Bank Statements - Sum of Cheques )</b></td>
                                                    <td class="text-end"><b>{{ number_format($finalstmt,2,'.','') }}</b></td>
                                                </tr>
                                                <tr>
                                                    @php $diff = number_format($acc_book-$finalstmt,2,'.',''); @endphp
                                                    <td></td>
                                                    <td style="text-align:center"><b>Difference ( Acct Book - Total )</b></td>
                                                    <td style="text-align:right"><b>{{  $diff==0?'Nil':$diff }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>   
                                        <input type="hidden" id="as_receipt_book" value="{{ $acc_book }}">                                     
                                    </div>
                                </div>
                                {{-- Bank Reconciliation --}}
                                {{-- SyDr,SyCR,Properties,Date Range Heading --}}
                                {{-- List of SY CR --}}
                                <div class="row">
                                    @if(request('id')!=63 && request('id')!=37 && request('id')!=11 && request('id')!=64)
                                    <div class="col-3">
                                        <table id="table8" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        SY CRs
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:left">Detail</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $cr=1;$crtotal=0; @endphp
                                                @forelse ($sycrs as $sycr) 
                                                @php
                                                    $crtotal += $sycr->sycr_amount;
                                                @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $cr++ }}</td>
                                                        <td style="text-align:left" class="text-wrap">{{ $sycr->sycr_label }}</td>
                                                        <td class="text-end">{{ number_format($sycr->sycr_amount,2,'.','') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                                @endforelse
                                                <tr>
                                                    <td></td>
                                                    <td class="fw-bold">Total</td>
                                                    <td class="text-end fw-bold"><b>{{ number_format($crtotal,2,'.','') }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>                                        
                                    </div>
                                    @endif
                                    {{-- List of SY CR --}}
                                    {{-- List of SY DR --}}
                                    @if(request('id')==63 || request('id')==37)
                                    <div class="col-3">
                                        <table id="table9" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        Sy DRS
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:left">Rank</th>
                                                    <th style="text-align:left">Name</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $dr=1;$drtotal=0; @endphp
                                                @forelse ($messbillsydrs as $sydr) 
                                                @php
                                                    $drtotal += $sydr->bill_total+$sydr->round_off;
                                                @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $dr++ }}</td>
                                                        <td style="text-align:left" class="text-wrap">{{ $sydr->officer_rank??'-' }}</td>
                                                        <td style="text-align:left" class="text-wrap">{{ $sydr->officer_name??'-' }}</td>
                                                        <td style="text-align:right">{{ Helper::numberFormat($sydr->bill_total+$sydr->round_off) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="4" class="text-center">No Data Found</td></tr>
                                                @endforelse
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="font-weight:bold"class="fw-bold">Total</td>
                                                        <td style="text-align:right;font-weight:bold" class="text-end fw-bold"><b>{{ Helper::numberFormat($drtotal) }}</b></td>
                                                    </tr>
                                                </tfoot>
                                            </tbody>
                                        </table>                                        
                                    </div>
                                    @else
                                    @if(request('id')!=11 && request('id')!=64)
                                    {{-- -------------------------------------------------------------------------- --}}
                                    <div class="col-3">
                                        <table id="table10" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        Sy DRS
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:left">Detail</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $dr=1;$drtotal=0; @endphp
                                                @forelse ($sydrs as $sydr) 
                                                @php
                                                    $drtotal += $sydr->sydr_amount;
                                                @endphp
                                                    <tr>
                                                        <td style="text-align:left">{{ $dr++ }}</td>
                                                        <td style="text-align:left" class="text-wrap">{{ $sydr->sydr_label }}</td>
                                                        <td class="text-end">{{ number_format($sydr->sydr_amount,2,'.','') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="text-center">No Data Found</td></tr>
                                                @endforelse
                                                <tr>
                                                    <td></td>
                                                    <td class="fw-bold">Total</td>
                                                    <td class="text-end fw-bold"><b>{{ number_format($drtotal,2,'.','') }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>                                        
                                    </div>
                                    @endif
                                    @endif
                                    {{-- List of SY DR --}}
                                    {{-- List of Properties --}}
                                    @if(request('id')!=63 && request('id')!=37 && request('id')!=11 && request('id')!=62 && request('id')!=64)
                                    <div class="col-4">
                                        <table id="table11" class="table  table-hover table-nowrap align-middle mb-0" border="1">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">
                                                        Details of Properties
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:left">S.No</th>
                                                    <th style="text-align:center">CRV No & Dt</th>
                                                    <th style="text-align:left">Nomenclature </th>
                                                    <th style="text-align:right">Amount (Rs)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $pro=2;$prototal=0;$row=0; @endphp
                                                <tr>
                                                    <td style="text-align:left">1</td>
                                                    <td style="text-align:center">BBF</td>
                                                    <td style="text-align:left">Property bal as on {{ date('t M Y', strtotime($start. ' -1 months')) }}</td>
                                                    {{-- $bbf_for_crv['voc_property'] from payment bbf --}}
                                                    @php $pay_property = isset($bbf_for_crv['voc_property'])?$bbf_for_crv['voc_property']:0; @endphp
                                                    <td style="text-align:right"><b>{{ $pay_property }}</b></td>
                                                </tr>
                                                @forelse ($crvs_properties as $property) 
                                                    @php
                                                        $crvdata = json_decode($property->crv,true); 
                                                        $json_count = count($crvdata);  
                                                        echo "<tr><td rowspan=".($json_count+1)." style='text-align:left'>".$pro++."</td></tr>";                                               
                                                    @endphp
                                                        @foreach($crvdata as $item)
                                                            @php $prototal += $item['amt']; @endphp
                                                            <tr>                                                                
                                                                <td style="text-align:center"  rowspan="1" class="text-wrap">{{ $item['lpno'] }}/{{ $item['date'] }}</td>
                                                                <td style="text-align:left" class="text-wrap">{{ $item['items'] }}</td>
                                                                <td style="text-align:right">{{ number_format($item['amt'],2,'.','') }}</td>
                                                            </tr>
                                                        @endforeach
                                                @empty
                                                    <tr><td colspan="4" class="text-center">No Data Found</td></tr>
                                                @endforelse
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align:center" class="fw-bold">Bal</td>
                                                    <td style="text-align:left" class="fw-bold">Property bal as on {{ date('t M Y',strtotime($start)) }}</td>
                                                    <td style="text-align:right" class="text-end fw-bold"><b>{{ number_format($pay_property+$prototal,2,'.','') }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>                                        
                                    </div>
                                    @endif
                                    {{-- List of Properties --}}
                                    {{-- Date Range --}}
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
                                    {{-- Date Range --}}
                                </div>
                                {{-- SyDr,SyCR,Properties,Date Range Heading --}}
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
                <input type="hidden" value="Receipt" name="voc_type">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="voc_date" class="form-label">Date</label>
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
                            <input type="number" min="0" name="voc_cash" class="form-control" id="voc_cash" placeholder="Cash Amount" value="0">
                            <span class="text-danger" id="voc_cash_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_bank" class="form-label">Bank(Rs.)</label>
                            <input type="number" min="0" name="voc_bank" class="form-control" id="voc_bank" placeholder="Bank Amount" value="0">
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
                                        <input type="number" min="0" name="{{ $name }}" class="form-control subcats" id="bank" placeholder="{{ $subcats->name }}">
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
                            <input type="number" min="0" name="voc_memo_stk" class="form-control" id="voc_memo_stk" placeholder="Enter Amount">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_property" class="form-label">Property</label>
                            <input type="number" min="0" name="voc_property" class="form-control" id="voc_property" placeholder="Enter Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_fd" class="form-label">FD</label>
                            <input type="number" min="0" name="voc_fd" class="form-control" id="voc_fd" placeholder="Enter Amount">
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="button">Submit Voucher</button>
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Credit BBF Detail</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="bbf-form" action="{{route('bbfadd')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $fundfor }}" name="voc_fund_type">
                <input type="hidden" value="{{ $cat_id }}" name="cat_id" id="bbf_cat_id">
                <input type="hidden" value="Receipt" name="voc_type">
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
                            <input type="number" min="0" name="voc_memo_stk" class="form-control" id="voc_memo_stk" placeholder="Enter Amount">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_property" class="form-label">Property</label>
                            <input type="number" min="0" name="voc_property" class="form-control" id="voc_property" placeholder="Enter Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_fd" class="form-label">FD</label>
                            <input type="number" min="0" name="voc_fd" class="form-control" id="voc_fd" placeholder="Enter Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_date" class="form-label">BBF Date</label>
                            <input type="date" name="voc_date" class="form-control" id="voc_date" value="{{ $start }}">
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
        $('#bbf-form').append('<input type="hidden" name="source_page" value="voucher">');
        
        // Add original category ID to process subcategories correctly
        $('#bbf-form input[name="original_cat_id"]').remove();
        $('#bbf-form').append('<input type="hidden" name="original_cat_id" value="{{ $cat_id }}">');
        
        // Auto BBF: Stay in same category, switch to Payment side
        $('#bbf_cat_id').val('{{ $cat_id }}');
        
        // Change voc_type to Payment (opposite of Receipt)
        $('#bbf-form input[name="voc_type"]').val('Payment');
        
        // Get balance values directly from the DOM (Balance row)
        const balanceCells = $('.balance');
        let cashTotal = 0;
        let bankTotal = 0;
        let propertyTotal = 0;
        let fdTotal = 0;
        let memoTotal = 0;
        
        console.log('Auto BBF: Getting values from Balance row');
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
        
        // Change modal title to indicate auto-population and cross-working
        const targetPageName = {{ $cat_id }} == 101 ? 'Payment' : 'Receipt';
        $('.modal-title').text('Auto BBF - Cross Working to ' + targetPageName + ' Page');
        
        toastr.success('BBF form populated for cross-working to ' + targetPageName + ' page!');
    });
    
    // Reset modal title when regular Add BBF is clicked
    $('#addBbfBtn').on('click', function() {
        $('.modal-title').text('Enter Credit BBF Detail');
        $('#bbf-form').attr('action', '{{ route("bbfadd") }}'); // Reset to regular BBF route
        $('#bbf_cat_id').val('{{ $cat_id }}'); // Reset to original cat_id
        $('#bbf-form input[name="voc_type"]').val('Receipt'); // Reset to Receipt
        $('#bbf-form input[name="source_page"]').remove(); // Remove source_page field
        $('#bbf-form input[name="original_cat_id"]').remove(); // Remove original_cat_id field
        $('#bbf-form')[0].reset();
    });

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
        // if (columnTotal.toFixed(2) > cashBankTotal.toFixed(2)) {
        //     toastr.error(`The sum of columns ${columnTotal} > ${cashBankTotal} the sum of cash and bank .`);
        // } else {
        //     $('#voucher-form').submit();
        // }
        $('#voucher-form').submit();
    });
});

$(document).ready(function() {
    $('.total').each(function(index) {
        var bbftotalValue = parseFloat($('.bbftotal').eq(index).text()) || 0;
        var messtotalValue = parseFloat($('.messtotal').eq(index).text()) || 0;
        var totalValue = parseFloat($(this).text()) || 0;
        var newTotal = totalValue + bbftotalValue + messtotalValue;
        $(this).text(newTotal.toFixed(2));
    });
});

$(document).ready(function() {
    //Get Grand Total From Total
    $('td.grand').each(function(index) {
        var total = parseFloat($('td.total').eq(index).text())||0;
        //var balance = parseFloat($('td.balance').eq(index).text());
        //var grand = total + balance;
        $(this).text(total.toFixed(2));
    });
    //Get balance grand-total
    $('td.balance').each(function(index) {
        var total = parseFloat($('td.total').eq(index).text())||0;
        var grand = parseFloat($('td.paymenttotal').eq(index).text())||0;
        var ballance = grand - total;
        $(this).text(ballance.toFixed(2));
    });

    //Total Balance Grand Below Right Side Total
    var total=balance=grand=0;
    $('.total').each(function() {
        // console.log($(this).text())
        total+=parseFloat($(this).text())||0;
    });
    $('.balance').each(function() {
        balance+=parseFloat($(this).text())||0;
    });
    $('.paymenttotal').each(function() {
        grand+=parseFloat($(this).text())||0;
    });
    // var cash_total = parseFloat($('#cash_total').text());
    // var bank_total = parseFloat($('#bank_total').text());
    // var sum = total + cash_total + bank_total;
    // var cash_balance = parseFloat($('#cash_balance').text());
    // var bank_balance = parseFloat($('#bank_balance').text());
    // var bal = balance;
    // var cash_grand = parseFloat($('#cash_grand').text());
    // var bank_grand = parseFloat($('#bank_grand').text());
    // var grd = grand + cash_grand + bank_grand;
    $('#toatlamt').text(total.toFixed(2))
    $('#balanceamt').text(balance.toFixed(2))
    $('#grandamt').text(grand.toFixed(2))  
    
    // $('#cash_balance').text($('#cash_total').text()-$('#cash_grand').text())
    // $('#bank_balance').text($('#bank_total').text()-$('#bank_grand').text())
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
            a.download = `${fundtype}-receipts-${start}-${end}.xlsx`;
            a.click();
            URL.revokeObjectURL(url);
        });
      
    });
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{ route("grandtotal") }}',
            type: 'POST',
            data: {
                assets: $('#assets').val(),
                liabilities: $('#liabilities').val(),
                rdate: $('#start-date').val(),
                book_amount: $('#as_receipt_book').val(),
                cat_id: {{ $cat_id }},
                voc_type: 'Receipt',
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                // if (response.status) {
                //     console.log(response.data);
                // }
            },
            error: function(xhr, status, error) {
                console.log("XHR Response: ", xhr.responseText);
                console.log("Status: ", status);
                console.log("Error: ", error);
            }
        });
    });
</script>

<!-- Total Allotment Modal -->
@if(isset($categoryInfo) && $categoryInfo->has_total_allotment)
<div class="modal fade" id="totalallotmentmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="totalAllotmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="totalAllotmentModalLabel">Fund Management for {{ $fundfor }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="fundTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="total-allotment-tab" data-bs-toggle="tab" data-bs-target="#total-allotment" type="button" role="tab">
                            <i class="las la-calculator me-1"></i>Total Allotment
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sent-pcda-tab" data-bs-toggle="tab" data-bs-target="#sent-pcda" type="button" role="tab">
                            <i class="las la-paper-plane me-1"></i>Amount Sent to PCDA
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="booked-pcda-tab" data-bs-toggle="tab" data-bs-target="#booked-pcda" type="button" role="tab">
                            <i class="las la-book me-1"></i>Amount Booked by PCDA
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="fundTabContent">
                    <!-- Total Allotment Tab -->
                    <div class="tab-pane fade show active" id="total-allotment" role="tabpanel">
                        <!-- Existing Total Allotments Table -->
                        @if(isset($totalAllotments) && $totalAllotments->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Existing Total Allotments</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Date</th>
                                            <th>Sub Category</th>
                                            <th>Amount (Rs.)</th>
                                            <th>Description</th>
                                            <th>Added By</th>
                                            <th>Created At</th>
                                            <th width="80">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($totalAllotments as $allotment)
                                        <tr>
                                            <td>{{ $allotment->allotment_date->format('d/m/Y') }}</td>
                                            <td>{{ $allotment->subcategory ? $allotment->subcategory->name : 'N/A' }}</td>
                                            <td class="text-end">{{ number_format($allotment->allotment_amount, 2) }}</td>
                                            <td>{{ $allotment->description ?? 'No description' }}</td>
                                            <td>{{ $allotment->user ? $allotment->user->name : 'Unknown' }}</td>
                                            <td>{{ $allotment->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('totalallotmentdelete', $allotment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this Total Allotment entry?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-info">
                                            <th colspan="3" class="text-end">Total Allotted Amount:</th>
                                            <th class="text-end">{{ number_format($totalAllotments->sum('allotment_amount'), 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                        </div>
                        @else
                        <div class="alert alert-info mb-4">
                            <i class="las la-info-circle"></i> No Total Allotments have been added for {{ $fundfor }} yet.
                        </div>
                        @endif

                        <!-- Add New Total Allotment Form -->
                        <h6 class="fw-bold mb-3">Add New Total Allotment</h6>
                        <form action="{{ route('totalallotmentadd') }}" class="auth-input" method="post">
                            @csrf
                            <input type="hidden" value="{{ $fundfor }}" name="fund_type">
                            <input type="hidden" value="{{ $cat_id }}" name="category_id">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="subcategory_id" class="form-label">Sub Category <span class="text-danger">*</span></label>
                                    <select name="subcategory_id" class="form-control" id="subcategory_id" required>
                                        <option value="">Select Sub Category</option>
                                        @foreach($subcategories as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="allotment_amount" class="form-label">Total Allotment Amount (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="0.01" name="allotment_amount" class="form-control" id="allotment_amount" placeholder="Enter Total Allotment Amount" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="allotment_date" class="form-label">Allotment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="allotment_date" class="form-control" id="allotment_date" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="allotment_description" class="form-label">Description</label>
                                    <textarea name="allotment_description" class="form-control" id="allotment_description" rows="3" placeholder="Enter description (optional)"></textarea>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="las la-plus-circle me-1"></i>Add Total Allotment
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Amount Sent to PCDA Tab -->
                    <div class="tab-pane fade" id="sent-pcda" role="tabpanel">
                        @php
                            $sentToPcda = isset($pcdaTransactions) ? $pcdaTransactions->where('transaction_type', 'sent_to_pcda') : collect();
                        @endphp
                        
                        @if($sentToPcda->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Amount Sent to PCDA Records</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Date</th>
                                            <th>Sub Category</th>
                                            <th>Amount (Rs.)</th>
                                            <th>Description</th>
                                            <th>Added By</th>
                                            <th>Created At</th>
                                            <th width="80">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sentToPcda as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                            <td>{{ $transaction->subcategory ? $transaction->subcategory->name : 'N/A' }}</td>
                                            <td class="text-end">{{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ $transaction->description ?? 'No description' }}</td>
                                            <td>{{ $transaction->user ? $transaction->user->name : 'Unknown' }}</td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('pcdatransactiondelete', $transaction->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this PCDA transaction?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-info">
                                            <th colspan="3" class="text-end">Total Sent to PCDA:</th>
                                            <th class="text-end">{{ number_format($sentToPcda->sum('amount'), 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                        </div>
                        @else
                        <div class="alert alert-info mb-4">
                            <i class="las la-info-circle"></i> No amounts have been sent to PCDA for {{ $fundfor }} yet.
                        </div>
                        @endif

                        <!-- Add Amount Sent to PCDA Form -->
                        <h6 class="fw-bold mb-3">Add Amount Sent to PCDA</h6>
                        <form action="{{ route('pcdatransactionadd') }}" class="auth-input" method="post">
                            @csrf
                            <input type="hidden" value="{{ $fundfor }}" name="fund_type">
                            <input type="hidden" value="{{ $cat_id }}" name="category_id">
                            <input type="hidden" value="sent_to_pcda" name="transaction_type">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pcda_subcategory_id" class="form-label">Sub Category</label>
                                    <select name="subcategory_id" class="form-control" id="pcda_subcategory_id">
                                        <option value="">Select Sub Category (Optional)</option>
                                        @foreach($subcategories as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="pcda_amount" class="form-label">Amount Sent (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="0.01" name="amount" class="form-control" id="pcda_amount" placeholder="Enter Amount Sent to PCDA" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pcda_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
                                    <input type="date" name="transaction_date" class="form-control" id="pcda_date" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="pcda_description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="pcda_description" rows="3" placeholder="Enter description (optional)"></textarea>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="las la-paper-plane me-1"></i>Add Amount Sent to PCDA
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Amount Booked by PCDA Tab -->
                    <div class="tab-pane fade" id="booked-pcda" role="tabpanel">
                        @php
                            $bookedByPcda = isset($pcdaTransactions) ? $pcdaTransactions->where('transaction_type', 'booked_by_pcda') : collect();
                        @endphp
                        
                        @if($bookedByPcda->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Amount Booked by PCDA Records</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Date</th>
                                            <th>Sub Category</th>
                                            <th>Amount (Rs.)</th>
                                            <th>Description</th>
                                            <th>Added By</th>
                                            <th>Created At</th>
                                            <th width="80">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookedByPcda as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                            <td>{{ $transaction->subcategory ? $transaction->subcategory->name : 'N/A' }}</td>
                                            <td class="text-end">{{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ $transaction->description ?? 'No description' }}</td>
                                            <td>{{ $transaction->user ? $transaction->user->name : 'Unknown' }}</td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('pcdatransactiondelete', $transaction->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this PCDA transaction?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-info">
                                            <th colspan="3" class="text-end">Total Booked by PCDA:</th>
                                            <th class="text-end">{{ number_format($bookedByPcda->sum('amount'), 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                        </div>
                        @else
                        <div class="alert alert-info mb-4">
                            <i class="las la-info-circle"></i> No amounts have been booked by PCDA for {{ $fundfor }} yet.
                        </div>
                        @endif

                        <!-- Add Amount Booked by PCDA Form -->
                        <h6 class="fw-bold mb-3">Add Amount Booked by PCDA</h6>
                        <form action="{{ route('pcdatransactionadd') }}" class="auth-input" method="post">
                            @csrf
                            <input type="hidden" value="{{ $fundfor }}" name="fund_type">
                            <input type="hidden" value="{{ $cat_id }}" name="category_id">
                            <input type="hidden" value="booked_by_pcda" name="transaction_type">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="booked_subcategory_id" class="form-label">Sub Category</label>
                                    <select name="subcategory_id" class="form-control" id="booked_subcategory_id">
                                        <option value="">Select Sub Category (Optional)</option>
                                        @foreach($subcategories as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="booked_amount" class="form-label">Amount Booked (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="0.01" name="amount" class="form-control" id="booked_amount" placeholder="Enter Amount Booked by PCDA" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="booked_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
                                    <input type="date" name="transaction_date" class="form-control" id="booked_date" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="booked_description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="booked_description" rows="3" placeholder="Enter description (optional)"></textarea>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="las la-book me-1"></i>Add Amount Booked by PCDA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
