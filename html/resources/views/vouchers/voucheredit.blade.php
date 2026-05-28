@extends('layouts.master')
@section('maincontent')
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <style>
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
    <div class="page-content">
        <div class="container-fluid">
            <x-bar-after-top-bar title="{{ $title }}" button="" count="0" target="" currentmonth="MONTH OF {{ now()->format('M, Y') }}"/>
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
                            <form id="voucher-form" action="{{route('voucherupdate')}}" class="auth-input" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $voucher->voc_fund_type }}" name="voc_fund_type">
                                <input type="hidden" value="{{ $voucher->category_id }}" name="cat_id">
                                <input type="hidden" value="{{ $voucher->voc_type }}" name="voc_type"> 
                                <input type="hidden" value="{{ $voucher->voc_file }}" name="filePath"> 
                                <input type="hidden" value="{{ $voucher->id }}" name="id"> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="voc_date" class="form-label">Date No</label>
                                            <input type="date" name="voc_date" class="form-control" id="voc_date" value="{{ $voucher->voc_date }}">
                                            <span class="text-danger" id="voc_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="voc_no" class="form-label">Voucher No</label>
                                            <input type="text" name="voc_no" class="form-control" id="voc_no" placeholder="Enter VR No." value="{{ $voucher->voc_no }}">
                                            <span class="text-danger" id="voc_no_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="voc_file" class="form-label">Upload Voucher</label>
                                            <input type="file" name="voc_file" class="form-control" id="voc_file" placeholder="Upload Voucher">
                                            @if($voucher->voc_file!='')
                                            <a href="{{ asset('storage/'.$voucher->voc_file) }}" target="_blank">View</a> 
                                            @endif
                                            <span class="text-danger" id="voc_file_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="voc_whom" class="form-label">From Whom</label>
                                            <input type="text" name="voc_whom" class="form-control" id="voc_whom" placeholder="Enter From" value="{{ $voucher->voc_whom }}">
                                            <span class="text-danger" id="voc_whom_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="voc_acc" class="form-label">What Account</label>
                                            <input type="text" name="voc_acc" class="form-control" id="voc_acc" placeholder="Enter Account" value="{{ $voucher->voc_acc }}">
                                            <span class="text-danger" id="voc_acc_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="voc_cash" class="form-label">Cash(Rs.)</label>
                                            <input type="number" min="0" name="voc_cash" class="form-control" id="voc_cash" placeholder="Cash Amount" value="{{ $voucher->voc_cash }}">
                                            <span class="text-danger" id="voc_cash_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="voc_bank" class="form-label">Bank(Rs.)</label>
                                            <input type="number" min="0" name="voc_bank" class="form-control" id="voc_bank" placeholder="Bank Amount" value="{{ $voucher->voc_bank }}">
                                            <span class="text-danger" id="voc_bank_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset class="border rounded-3 p-3">
                                            <legend class="float-none w-auto px-3" >Credit to Ledger Accounts</legend>
                                            <div class="row">
                                                @php
                                                    //helper function for merging new and old categories with db 
                                                    $voc_json =  \App\Helpers\Helper::categories_voc_json($voucher->voc_json,$voucher->category_id); @endphp
                                                @foreach ($voc_json as $key=>$value)
                                                    @php 
                                                        $name = strtolower(preg_replace('/\s+/', '_', trim($key)));
                                                        $place = ucwords(strtolower(preg_replace('/_+/', ' ', trim($key))));
                                                    @endphp
                                                    <div class="col-md-4 mb-3">
                                                        <input type="number" min="0" name="{{ $name }}" class="form-control subcats" id="{{ $name }}" placeholder="{{ $place }}" value="{{ $value }}">
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
                                            <input type="number" min="0" name="voc_memo_stk" class="form-control" id="voc_memo_stk" placeholder="Enter Amount" value="{{ $voucher->voc_memo_stk }}">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="voc_property" class="form-label">Property</label>
                                            <input type="number" min="0" name="voc_property" class="form-control" id="voc_property" placeholder="Enter Amount" value="{{ $voucher->voc_property }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-primary w-100" id="submitBtn" type="button">Update Voucher</button>
                                </div>
                            </form>
                            <!-- end table responsive -->
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
<script>
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
        //     toastr.error('The total amount of columns is greater the sum of cash and bank.');
        // } else {
        //     $('#voucher-form').submit();
        // }
        $('#voucher-form').submit();
    });
});
</script>
<!-- end main content-->
@endsection