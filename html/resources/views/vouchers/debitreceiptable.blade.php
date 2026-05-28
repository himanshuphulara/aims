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
            <x-bar-after-top-bar title="{{ $title }}" button="Add Voucher" count="0" target="addvoucher" currentmonth=""/>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0">
                                    <tr>
                                        <th rowspan="2" class="align-top">Action</th>
                                        <th rowspan="2" class="align-top">Date</th>
                                        <th rowspan="2" class="align-top">Vr No</th>
                                        <th rowspan="2" class="align-top">From whom recd</th>
                                        <th rowspan="2" class="align-top">On what account</th>
                                        <th rowspan="2" class="align-top">CASH (Rs)</th>
                                        <th rowspan="2" class="align-top">BANK (Rs)</th>
                                        <th colspan="{{ count($categories) + 1 }}" class="text-center align-top">Credit to Ledger Accounts</th>
                                    </tr>
                                    <tr>
                                        @foreach ($categories as $cate)
                                            <th class="align-top">{{ $cate->name }}</th>                                          
                                        @endforeach
                                        <th class="align-top">Properties</th> 
                                    </tr>
                                    <tbody>
                                        @php
                                            $cash=$bank=$v=0;
                                            $cattotal = [];
                                        @endphp
                                        @foreach($vouchers as $voc)
                                        @php
                                            $cash += $voc->voc_cash;
                                            $bank += $voc->voc_bank;
                                            foreach($voc->voc_json as $key=>$value){
                                                if($value!=''){
                                                    if (isset($cattotal[$key])) {
                                                        $cattotal[$key] += $value;
                                                    } else {
                                                        $cattotal[$key] = $value;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('voucherdelete',[$voc->id,$voc->category_id]) }}" class="btn btn-danger btn-sm" id="{{ $voc->id }}" onclick="return confirm('Are you sure?')" ><i class="las la-trash-alt fs-18 align-middle"></i></a>
                                                <a href="{{ route('voucheredit',[$voc->id]) }}" class="btn btn-primary btn-sm" id="{{ $voc->id }}"><i class="las la-pen-alt fs-18 align-middle"></i></a>
                                            </td>
                                            <td>{{ $voc->voc_date }}</td>
                                            <td><a href="{{ asset('storage/'.$voc->voc_file) }}" target="_blank">{{ $voc->voc_no }}</a> </td>
                                            <td>{{ $voc->voc_whom }}</td>
                                            <td>{{ $voc->voc_acc }}</td>
                                            <td class="text-end">{{ $voc->voc_cash }}</td>
                                            <td class="text-end">{{ $voc->voc_bank }}</td>
                                            {{-- categories --}}
                                            @foreach($voc->voc_json as $json)
                                                @if($json!='')
                                                    <td class="text-end">{{ $json }}</td>
                                                @else
                                                    <td class="text-end">0</td>
                                                @endif
                                            @endforeach
                                            {{-- properties --}}
                                            <td>Properties</td>
                                        @endforeach
                                        @php //print_r($cattotal); 
                                        @endphp
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end">Total</td>
                                            <td class="text-end">{{ $cash }}</td>
                                            <td class="text-end">{{ $bank }}</td>
                                            @foreach($cattotal as $c)
                                                <td class="text-end">{{$c}}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end">Balance</td>
                                            <td colspan="12"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end">G/Total</td>
                                            <td colspan="12"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                            <label for="voc_date" class="form-label">Date No</label>
                            <input type="date" name="voc_date" class="form-control" id="voc_date">
                            <span class="text-danger" id="voc_date_error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="voc_no" class="form-label">Voucher No</label>
                            <input type="text" name="voc_no" class="form-control" id="voc_no" placeholder="Enter Vr No.( R No. )">
                            <span class="text-danger" id="voc_no_error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="voc_file" class="form-label">Voucher Upload</label>
                            <input type="file" name="voc_file" class="form-control" id="voc_file" placeholder="Enter Voucher No.">
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
                                        <input type="number" min="0" name="{{ $name }}" class="form-control subcats" id="bank" placeholder="{{ $subcats->name }}">
                                    </div>                                    
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary w-100" id="submitBtn" type="button">Submit Voucher</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- end main content-->
<script>
$(document).ready(function() {
    $('#voucher-form').on('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);
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
            toastr.error('The total amount of columns is greater the sum of cash and bank.');
        } else {
            $('#voucher-form').submit();
        }
    });
});
</script>
@endsection