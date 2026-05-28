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
</style>
    <div class="page-content">
        <div class="container-fluid">
            @php $button = auth()->user()->hasPermissions('voucheradd')?'Add Voucher':''; @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{ count($vouchers) }}" target="addvoucher" currentmonth="Payment (Credit) MONTH OF {{ now()->format('M, Y') }}"/>
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
                            <button id="submit" class="btn btn-sm btn-primary mt-4"><i class="me-1 las la-search"></i></button>                                                    
                            <a href="{{ route('voucherp',[request()->route()->parameters['id']]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                <i class="las la-redo-alt"></i>
                            </a>                                                    
                        </div>                                                     
                        </form>                        
                        <button id="btnExport" class="btn btn-sm btn-primary float-end mt-4" start-date="{{ $start }}" end-date="{{ $end }}" month="Payment (Credit) MONTH OF {{ now()->format('M, Y') }}"><i class="me-1 las la-file-export"></i> Export</button> 
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="table1">
                                    <tr>
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
                                        <th class="align-top fixed">Properties</th> 
                                    </tr>
                                    <tbody>
                                        @php
                                            $cash=$bank=$v=0;
                                            $cattotal = [];
                                            $tables = [];$tab=0;
                                        @endphp
                                        @foreach($vouchers as $voc)
                                        @php
                                            $cash += $voc->voc_cash;
                                            $bank += $voc->voc_bank;
                                            foreach($voc->voc_json as $key=>$value){
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
                                            <td class="text-end table-secondary">{{ $voc->voc_cash }}</td>
                                            <td class="text-end table-secondary">{{ $voc->voc_bank }}</td>
                                            {{-- categories --}}
                                            @foreach($voc->voc_json as $json)
                                                @if($json!='')
                                                    <td class="text-end table-secondary">{{ $json }}</td>
                                                @else
                                                    <td class="text-end table-secondary">0</td>
                                                @endif
                                            @endforeach
                                            {{-- properties --}}
                                            <td>
                                                @if(auth()->user()->hasPermissions('crv'))
                                                <a href="{{ route('crv',[$voc->id,$voc->category_id,$voc->voc_no]) }}" class="btn btn-primary btn-sm">CRV<a>
                                                @endif
                                                @if(auth()->user()->hasPermissions('niv'))
                                                <a href="{{ route('niv',[$voc->id,$voc->category_id,$voc->voc_no]) }}" class="btn btn-primary btn-sm">NIV<a>
                                                @endif
                                            </td>
                                        @endforeach
                                        </tr>
                                        <tr>
                                            @php $col=(auth()->user()->hasPermissions('voucherdelete') || auth()->user()->hasPermissions('voucheredit'))?5:4; @endphp
                                            <td colspan="{{ $col }}" class="text-end">Total</td>
                                            <td class="text-end">{{ $cash }}</td>
                                            <td class="text-end">{{ $bank }}</td>
                                            @foreach($cattotal as $c)
                                                <td class="text-end total table-secondary">{{$c??0}}</td>
                                            @endforeach
                                            <td id="toatlamt" class="text-end"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="{{ $col }}" class="text-end">Balance</td>
                                            <td class="text-end"></td>
                                            <td class="text-end"></td>
                                            @foreach($cattotal as $c)
                                                <td class="text-end balance table-secondary">10</td>
                                            @endforeach
                                            <td id="balanceamt" class="text-end"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="{{ $col }}" class="text-end">G/Total</td>
                                            <td class="text-end"></td>
                                            <td class="text-end"></td>
                                            @foreach($cattotal as $c)
                                                <td class="text-end grand table-secondary"></td>
                                            @endforeach
                                            <td id="grandamt" class="text-end fw-bold"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-2">
                                        <table id="table2" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center">Assets</th>
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
                                                <tr><td><b>Total</b></td><td class="text-end">{{ $assets+$cash+$bank }}</td></tr>
                                                <tr><td><b>Property</b></td><td class="text-end">0.00</td></tr>
                                                <tr><td><b>Grand</b></td><td class="text-end">0.00</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-2">
                                        <table id="table3" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center">Liabilities</th>
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
                                                <tr><td><b>Total</b></td><td class="text-end">{{ $liabilities }}</td></tr>
                                                <tr><td><b>Property</b></td><td class="text-end">0.00</td></tr>
                                                <tr><td><b>Grand</b></td><td class="text-end">0.00</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-2">
                                        <table id="table3" class="table  table-hover table-nowrap align-middle mb-0">
                                            <thead>
                                                <th colspan="2" class="text-center">Bank Reconciliation</th>
                                            </thead>
                                            <tbody>
                                                <tr><td><b>Cash in bank as per Acc. Book</b></td><td class="text-end">0.00</td></tr>
                                                <tr><td><b>Cash in bank as per statement</b></td><td class="text-end">0.00</td></tr>
                                                <tr><td><b>Difference</b></td><td class="text-end">Nil</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
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
<div class="modal fade" id="addvoucher" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="date" name="voc_date" class="form-control" id="voc_date">
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
                            <input type="number" min="0" name="voc_cash" class="form-control" id="voc_cash" placeholder="Cash Amount">
                            <span class="text-danger" id="voc_cash_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="voc_bank" class="form-label">Bank(Rs.)</label>
                            <input type="number" min="0" name="voc_bank" class="form-control" id="voc_bank" placeholder="Bank Amount">
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
                                        <input type="number" min="0" name="{{ $name }}" class="form-control subcats small-input" id="bank" placeholder="{{ $subcats->name }}">
                                    </div>                                    
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="button">Submit Voucher</button>
                    {{-- <button class="btn btn-primary w-100" id="submitBtnSpin"><i class="las la-spinner"></i></button> --}}
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->
<script>
$(document).ready(function() {
    // $('#submitBtnSpin').hide();
    $('#voucher-form').on('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        // $('#submitBtn').hide();
        // $('#submitBtnSpin').show();
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
                // $('#submitBtn').show();
                // $('#submitBtnSpin').hide();
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
        var cash = parseFloat($('#voc_cash').val()) || 0;
        var bank = parseFloat($('#voc_bank').val()) || 0;
        var cashBankTotal = cash + bank;

        // Calculate the total of column amounts
        var columnTotal = 0;
        $('.subcats').each(function() {
            columnTotal += parseFloat($(this).val()) || 0;
        });

        // Check if column total matches cash + bank total
        if (columnTotal > cashBankTotal) {
            toastr.error(`The sum of columns ${columnTotal} > ${cashBankTotal} the sum of cash and bank .`);
        } else {
            $('#voucher-form').submit();
        }
    });
});

$(document).ready(function() {
    //Get Grand Total From Total-Balance
    $('td.grand').each(function(index) {
        var total = parseFloat($('td.total').eq(index).text());
        var balance = parseFloat($('td.balance').eq(index).text());
        var grand = total + balance;
        $(this).text(grand);
    });

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
    $('#toatlamt').text(total.toFixed(2))
    $('#balanceamt').text(balance.toFixed(2))
    $('#grandamt').text(grand.toFixed(2))    
});
</script>

<script src="{{ asset('assets/js/exceltable.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#btnExport").click(function() {
            start = $(this).attr('start-date');
            end = $(this).attr('end-date');
            month = $(this).attr('month');
            // Create a new workbook
                var wb = XLSX.utils.book_new();
                var allData = [];

                // Collect data from each table
                var ws = XLSX.utils.table_to_sheet(document.getElementById('table1'));
                    var rangefirstt1 = XLSX.utils.decode_range(ws['!ref']); // Get range of current table
                    rangefirsttable = rangefirstt1.e.c;

                $('table').each(function() {
                    var ws = XLSX.utils.table_to_sheet(this);
                    var range = XLSX.utils.decode_range(ws['!ref']); // Get range of current table
                    // console.log(range.e.c)
                    // Add current table data to allData
                    for (var row = range.s.r; row <= range.e.r; row++) {
                        var rowData = [];
                        for (var col = range.s.c; col <= range.e.c; col++) {
                            var cell = ws[XLSX.utils.encode_cell({r: row, c: col})]; 
                            // console.log(cell)                        
                                rowData.push(cell); // Push processed cell value
                        }
                        allData.push(rowData); // Push row data to allData
                    }
                    // Add a blank row between tables for separation
                    allData.push([]); // Adds a blank row
                });
                // console.log(allData[2][0]['l']['Target'])
                const dateLine = [`Date Range: ${start} - ${end}`];
                data = [dateLine, ...allData];
                // data = allData;
                // Create a new worksheet from the combined data
                var wsCombined = XLSX.utils.aoa_to_sheet(data);
                
                wsCombined['!cols'] = [
                { wpx: 150 },
                { wpx: 80 }, 
                ];

                wsCombined['!merges'] = [
                    { s: { r: 0, c: 0 }, e: { r: 0, c: rangefirsttable } } // Merges cells A1, B1, and C1
                ];
                // wsCombined["A1"].s = {
                //     alignment: {
                //         horizontal: "center",
                //         vertical: "center"
                //     }
                // };
                // wsCombined['!autofilter'] = {
                // ref: "A1:C1",
                // };

                // Append the worksheet to the workbook
                XLSX.utils.book_append_sheet(wb, wsCombined, "CombinedData");

                // Export the workbook to a file
                XLSX.writeFile(wb, `Credit-Payment-${start}-${end}.xlsx`);
            });
      
    });
</script>
@endsection