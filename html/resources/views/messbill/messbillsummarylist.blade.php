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
div#tbs {
    height: 700px;
    overflow-y: auto;
    overflow-x: auto;
}
.btn {
    margin-left: 10px; /* Space between buttons */
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
                $button = auth()->user()->hasPermissions('useradd')?'Add Mess Bill Summary':''; 
                $cat_id = request('cat_id');
                // print_r($drodownofficecheck);
                $officersIds = [];
                foreach($drodownofficecheck as $check){
                    $officersIds[$check->officer_id] = $check->officer_id;
                }
                // print_r($officersIds);
            @endphp
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
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{count($messbill)}}" target="addofficer" currentmonth="MesBill Summary MONTH OF {{ date('M, Y',strtotime($start)) }}" />            
            <div class="row">                
                <div class="col-xs-6">
                    <form action="{{ route('messbilllist',[$cat_id]) }}">
                        <div class="date-range-picker float-start">
                            <div class="col-xs-2">
                                <label for="start-date">Start Date:</label>
                                <input type="date" class="form-control" name="start_date" id="start-date" value="{{ $start }}">   
                                </div>
                                <div class="col-xs-2">                     
                                <label for="end-date">End Date:</label>
                                <input type="date" class="form-control" name="end_date" id="end-date" value="{{ $end }}">   
                                </div>
                                {{-- <div class="col-xs-2">
                                <label for="start-date">Search</label>
                                <input type="text" class="form-control" name="search" id="search" value="{{ $search }}">    
                                </div>                          --}}
                                <button id="submit" class="btn btn-sm btn-primary mt-4"><i class="me-1 las la-search"></i></button>  
                                <a href="{{ route('messbilllist',[$cat_id]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                    <i class="las la-redo-alt"></i>
                                </a>                        
                        </div>                      
                    </form>
                    <button id="btnExport" class="btn btn-sm btn-primary float-end mt-4" start-date="{{ $start }}" end-date="{{ $end }}" month="MesBill Summary MONTH OF {{ now()->format('M, Y') }}"><i class="me-1 las la-file-export"></i>Export</button>              
                    <a href="{{ route('messbillcategory',[$cat_id,$start,$end]) }}" class="btn btn-primary btn-sm float-end mt-4">Add Sub Category</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card" id="tables-container">
                                {{-- Date Range --}}
                                <table id="date-range-table" style="width:100%; border-collapse:collapse;" class="d-none">
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
                                <div id="tbs">
                                    <table class="table table-hover table-nowrap align-middle mb-0" id="myTable">
                                        <thead>
                                            <tr class="text-muted text-uppercase thead">
                                                @if(auth()->user()->role==1)
                                                    <th scope="align-top fixed" style="width: 12%;">Action</th>
                                                @endif
                                                <th scope="align-top">S.No</th>
                                                <th scope="align-top">Rank</th>
                                                <th scope="align-top">Name</th>
                                                <th scope="align-top">Arrears</th>
                                                <th scope="align-top">No Of Days</th>
                                                {{-- <th scope="align-top">Per Day Messing</th> --}}
                                                @foreach ($categories as $cate)
                                                @php $catname = strtolower(str_replace(" ", "_", $cate->name)) @endphp
                                                    <th class="align-top">{{ $cate->name }}</th>                                                
                                                    @foreach ($subcats as $subcat)
                                                    @if($subcat->main_category == $catname)
                                                        <th class="align-top">{{ $subcat->subcategory_name }}</th>
                                                    @endif
                                                    @endforeach                                                
                                                @endforeach
                                                <th scope="align-top">Total</th>
                                                <th scope="align-top">R/Off</th>
                                                <th scope="align-top">G/Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $i=1; $officersub=$messsub=[];$sum = 0;$sums=[];$billtotal=$perday=0;@endphp
                                                @forelse ($messbill as $mess)
                                                        @php
                                                        $bills = json_decode($mess->bill_json,true);
                                                        $subcatgs_json = json_decode($mess->subcatgs_json,true);
                                                        // print_r($subcatgs_json);
                                                        // print_r($subcatgs_json);die;
                                                            // $foundMatch = true;
                                                            // $messsub=[];//this is for empty array after each loop complete
                                                            // foreach($bills as $key=>$value){
                                                            //     $messsub[$key] = $value;
                                                            // } 
                                                            // print_r($messsub); 
                                                            //perday total
                                                            $perday += $mess->no_of_days*$mess->per_day_messing;
                                                            // total of messbill all rows
                                                            $billtotal+=$mess->bill_total;                          
                                                        @endphp
                                                        <tr>
                                                            <td class="fixed">
                                                                <a href="{{ route('messbilledit',[$mess->id,$mess->category_id,$start,$end]) }}" class="btn btn-primary btn-sm"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                                                <a href="{{ route('messbilldelete',[$mess->id,$mess->category_id,$start,$end]) }}" class="btn btn-danger btn-sm" id="{{ $mess->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
                                                                <a href="{{ route('officermessbillpdf',[$mess->id,$mess->category_id,$start,$end]) }}" class="btn btn-danger btn-sm" id="{{ $mess->id }}"><i class="las la-file-download"></i></a>
                                                            </td>   
                                                            <td class="align-top">{{$i++}}</td> 
                                                            <td class="align-top">{{$mess->officer_rank}}</td>
                                                            <td class="align-top">{{$mess->officer_name}}</td>
                                                            <td class="align-top text-end">{{Helper::numberFormat($mess->arrears)}}</td>
                                                            <td class="align-top text-end">{{$mess->no_of_days??0}}</td>
                                                            {{-- <td class="align-top text-end">{{Helper::numberFormat($mess->no_of_days*$mess->per_day_messing)}}</td> --}}
                                                            @foreach ($bills as $key=>$value)
                                                                {{-- @php 
                                                                    if (!isset($sums[$key])) {
                                                                        $sums[$key] = 0;
                                                                    }
                                                                    $sums[$key] += $value;  
                                                                @endphp --}}
                                                                <td class="align-top text-end">{{ Helper::numberFormat($value) }}</td>
                                                                @if(isset($subcatgs_json[$key]))
                                                                @foreach ($subcatgs_json[$key] as $subcat=>$value)
                                                                    <td class="align-top text-end">{{ Helper::numberFormat($value) }}</td>
                                                                @endforeach  
                                                                @endif
                                                            @endforeach
                                                            <td class="total text-end align-top">{{ Helper::numberFormat($mess->bill_total) }}</td>
                                                            <td class="total text-end align-top">{{ Helper::numberFormat($mess->round_off) }}</td>
                                                            <td class="total text-end align-top">{{ Helper::numberFormat($mess->bill_total+$mess->round_off) }}</td>
                                                        </tr>
                                                    @empty  
                                                    <tr><td colspan=6 class="text-center">No Data Found</td></tr>
                                                @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end" style="text-align:right"><b>Total</b></td>
                                                <td class="text-danger fw-bold text-end">{{ Helper::numberFormat($messbill->sum('arrears')) }}</td>
                                                <td class="text-danger fw-bold text-end">{{ $messbill->sum('no_of_days') }}</td>
                                                {{-- <td class="text-danger fw-bold text-end">{{ Helper::numberFormat($perday) }}</td> --}}
                                                @foreach ($sortedArray as $key=>$value)
                                                    <td class="text-danger fw-bold text-end">{{ Helper::numberFormat($value) }}</td>
                                                @endforeach
                                                {{-- Sum Of All Columns Rows in Footer --}}
                                                <td class="text-danger fw-bold text-end">{{ Helper::numberFormat($billtotal) }}</td>
                                                <td class="text-danger fw-bold text-end">{{ Helper::numberFormat($messbill->sum('round_off')) }}</td>
                                                <td class="text-danger fw-bold text-end">{{ Helper::numberFormat($billtotal+$messbill->sum('round_off')) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                {{-- messbill summary detail --}}
                                <div class="row mb-3">
                                    {{-- All Assets --}}
                                    <div class="col-4 offset-4">
                                        <table id="table2" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center" style="text-align:center;font-size:15px;font-weight:bold;background-color:#f2f2f2;border:1px solid #ddd;">SUMMARY OF MESS BILL</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($categories as $cate)
                                                    @php $catname = strtolower(str_replace(" ", "_", $cate->name)) @endphp
                                                    <tr>
                                                        <td class="align-top text-wrap">
                                                            @if($catname == 'ent_fund')
                                                            {{ $cate->name }} + r/off
                                                            @else
                                                            {{ $cate->name }}
                                                            @endif                                                
                                                            @foreach ($subcats as $subcat)
                                                                @if($subcat->main_category == $catname)
                                                                        + {{ $subcat->subcategory_name }}
                                                                @endif
                                                            @endforeach 
                                                        </td>  
                                                        <td class="text-end">{{ $result[$catname] }}</td>      
                                                    </tr>                                           
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-end"><b>Total</b></td>
                                                    <td class="text-end"><b>{{ Helper::numberFormat(array_sum($result)) }}</b></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
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
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Officer Mess Bill Summary</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('messbilladd')}}" class="auth-input" id="messbill-summary" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="{{$cat_id}}">
                <input type="hidden" name="officer_rank" id="officer_rank">
                <input type="hidden" name="officer_name" id="officer_name">
                <input type="hidden" name="sub_cat_date" value="{{ $start }}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <label for="officer_rank" class="form-label">Select Officer</label>
                        <select class="form-select mb-3" name="officer_id" id="ofc_on_change" required>
                            <option value="">Select Officer</option>
                            @foreach ($officerlist as $ofc)
                                @if(!isset($officersIds[$ofc->id]))
                                <option value="{{$ofc->id}}" name="{{$ofc->officer_name}}" rank="{{$ofc->officer_rank}}" subs="{{json_encode($ofc->subscriptions_json, JSON_PRETTY_PRINT)}}">{{$ofc->officer_name}}</option>
                                @endif
                            @endforeach
                            <option value="other" name="" rank="" subs="{}">Other</option>
                        </select>
                    </div>
                </div>
                <div class="row" id="othershowhide" style="display: none">
                    <div class="col-4 mb-3">
                        <label for="other_officer_rank" class="form-label">Enter Rank</label>
                        <input type="test" name="other_officer_rank" class="form-control" id="other_officer_rank" placeholder="Enter Rank">
                    </div>
                    <div class="col-8 mb-3">
                        <label for="other_officer_name" class="form-label">Enter Name</label>
                        <input type="test" name="other_officer_name" class="form-control" id="other_officer_name" placeholder="Enter Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="officer_arrears" class="form-label">Arrears</label>
                        <input type="number" step="0.01" name="arrears" class="form-control" id="officer_arrears" placeholder="Enter Amount">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="officer_no_of_days" class="form-label">No of days</label>
                        <input type="number" step="0.01" name="no_of_days" class="form-control" id="officer_no_of_days" placeholder="Enter Amount">
                    </div>    
                    {{-- <div class="col-4 mb-3">
                        <label for="officer_per_day_messing" class="form-label">Per days messing</label>
                        <input type="number" step="0.01" name="per_day_messing" class="form-control" id="officer_per_day_messing" placeholder="Enter Amount">
                    </div> --}}
                </div> 
                <div class="row">
                    <p>Categories</p>
                    @foreach ($categories as $cate)
                    @php $catname = strtolower(str_replace(" ", "_", $cate->name)); @endphp
                    <div class="col-3 mb-3" id="cate_value">
                        <label for="{{ $cate->name }}" class="form-label">{{ $cate->name }}</label>
                        <input type="number" step="0.01" name="catbill[{{$catname}}]" id="{{$catname}}" class="form-control cate_value" placeholder="{{ $cate->name }}">
                    </div>
                        @foreach ($subcats as $subcat)
                            @if ($subcat->main_category == $catname)
                            @php
                                $subcatgs = strtolower(str_replace(" ", "_", $subcat->subcategory_name));
                            @endphp
                                <div class="col-3 mb-3">
                                    <label for="{{ $subcat->subcategory_name }}" class="form-label">{{ $subcat->subcategory_name }}</label>
                                    <input type="number" step="0.01" name="subcatgs[{{ $subcat->main_category }}][{{ $subcatgs }}]" class="form-control subcates" id="{{ $subcatgs }}" placeholder="{{ $subcat->subcategory_name }}" value="{{ $subcat->amount }}" cat_name="{{ $subcatgs }}" cat_val="{{ $subcat->amount }}" 
                                    @if($subcat->amount!='') @readonly(true) @endif>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="bill_date" class="form-label">Date</label>
                        <input type="date" name="bill_date" value="{{$start}}" class="form-control" id="bill_date" placeholder="Bill Date" required>
                    </div>
                    {{-- <div class="col-6 mb-3">
                        <label for="round_off" class="form-label">Round Off</label>
                        <input type="number" step="0.01" name="round_off" class="form-control" id="round_off" placeholder="Round Off">
                    </div> --}}
                </div>      
                <div class="mt-2">
                    <button class="btn btn-primary w-100 text-uppercase" type="submit" id="submitBtn">Save Detail</button>
                </div>
            </form>
            <div id="loadingSpinner" class="loader" style="display:none;"></div>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->


<script>   
$('#messbill-summary').on('submit', function(event) {
        $('#loadingSpinner').show();
        $('#submitBtn').attr('disabled',true);
})
$('#ofc_on_change').on('change',function(){
    var subs = $(this).find('option').eq(this.selectedIndex).attr('subs');
    var name = $(this).find('option').eq(this.selectedIndex).attr('name');
    var rank = $(this).find('option').eq(this.selectedIndex).attr('rank');
    $('#officer_name').val(name);
    $('#officer_rank').val(rank);
    subs = JSON.parse(subs);
    // alert($(this).val())
    if($(this).val() == 'other'){
        $('#othershowhide').show()
        $('#other_officer_name').attr('required',true)
    }else{
        $('#othershowhide').hide()
        $('#other_officer_name').attr('required',false)
    }
    $('#cate_value input').each(function() {
        var inputId = $(this).attr('id');
        if (!(inputId in subs)) {
            $(this).val('');
            $(this).attr('readonly',false);
        }
    });
    $.each(subs, function(key, value) {
        $(`#${key}`).val(value);
        $(`#${key}`).attr('readonly',true);
    });
});
</script>

<script>
    $('#officer_arrears').on('blur',function(){
        $('#sy_dr').val($('#officer_arrears').val())
    })
</script>

<script src="{{ asset('assets/js/newexcelexport.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#btnExport").click(function() {
            start = $(this).attr('start-date');
            end = $(this).attr('end-date');
            month = $(this).attr('month');
            // Create a new workbook
            var excelData = '';

            // var dateRangeHtml = $('#date-range-table').prop('outerHTML');
            // excelData += dateRangeHtml + '<br/><br/>'; // Add space after the date range
            
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
            a.download = `export-${start}-${end}.xlsx`;
            a.click();
            URL.revokeObjectURL(url);
        });
      
    });

        $('#officer_no_of_days').on('keyup',function(){
        $('.subcates').each(function() {
                var sa = $(this).attr('cat_name');
                var val = parseFloat($(this).attr('cat_val'))||0;
                var noof = $('#officer_no_of_days').val()||0;
                var answer = noof*val;
                if(val!='' && noof!=''){
                    //because of special character @. we need to escape in jquery so we used js vanilla syntax for getElementById
                    document.getElementById(`${sa}`).value = answer.toFixed(2);
                }else{
                    // $(`#${sa}`).val(''); because of special character @. we need to escape in jquery so we used js vanilla syntax for getElementById
                    document.getElementById(`${sa}`).value;
                }
                // console.log("anser",noof*val)
            });
        });
</script>
@endsection
