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
    </style>
    <div class="page-content">
        <div class="container-fluid">
            {{-- <a href="{{ route('civpdf',[request()->route()->parameters['civ_id']]) }}" class="btn btn-sm btn-primary float-end">Download civ Pdf</a> --}}
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addciv"><i class="las la-plus me-1"></i> Add Voucher</button>
                    </div>
                </div>
                @endif
            </div> --}}
            <!-- end page title -->
            <form id="civ-form" action="{{route('astbupdate')}}" class="auth-input" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $astb->id }}" name="id">
                <input type="hidden" value="{{ $astb->category_id }}" name="cat_id">
                <input type="hidden" value="{{ $astb->parent_item_id }}" name="parent_item_id">
                <input type="hidden" value="{{ $astb->property_type }}" name="property_type">
                <input type="hidden" value="{{ request()->start }}" name="start">
                <input type="hidden" value="{{ request()->end }}" name="end">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="lpno" class="form-label">LPNO</label>
                            <input class="form-control" type="text" id="lpno" name="lpno" placeholder="LPNO." value="{{ $astb->lpno }}">
                        </div>
                    </div>
                    <div class="col-md-3">    
                        <div class="mb-2">
                            <label for="items" class="form-label">Nomenclature</label>
                            <input class="form-control" type="text" id="items" name="items" placeholder="Nomenclature" value="{{ $astb->items }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="au" class="form-label">A/U</label>
                            <input class="form-control" type="text" id="au" name="au" placeholder="A/U" value="{{ $astb->au }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="date" class="form-label">Date of Purchase</label>
                            <input class="form-control" type="date" id="date" name="date" placeholder="Date of Purchase" value="{{ $astb->date }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="qty" class="form-label">Qty</label>
                            <input class="form-control qty" type="number" id="qty" name="qty" placeholder="Aty" value="{{ $astb->qty }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="rate" class="form-label">Rate</label>
                            <input class="form-control rate" type="number" step="0.01" id="rate" name="rate" placeholder="Date" value="{{ $astb->rate }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label for="amt" class="form-label">Amount</label>
                            <input class="form-control amt" type="number" step="0.01" id="amt" name="amt" placeholder="Amount" value="{{ $astb->amt }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <fieldset class="border rounded-3 p-3">
                        <legend class="float-none w-auto px-3" >Serviceable Quantities</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="serviceable" class="form-label">Serviceable Qty</label>
                                    <input class="form-control" type="number" id="serviceable" name="serviceable" placeholder="Enter Serviceable Qty" value="{{ $astb->serviceable }}">
                                </div>
                            </div>  
                        </div>
                    </fieldset>
                    <fieldset class="border rounded-3 p-3">
                        <legend class="float-none w-auto px-3" >Unserviceable Quantities</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="repairable" class="form-label">Repairable</label>
                                    <input class="form-control" type="number" id="repairable" name="repairable" placeholder="Enter Repairable Qty" value="{{ $astb->repairable }}">
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="auction" class="form-label">Auction</label>
                                    <input class="form-control" type="number" id="auction" name="auction" placeholder="Enter Auction Qty" value="{{ $astb->auction }}">
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="destroyable" class="form-label">Destroyable</label>
                                    <input class="form-control" type="number" id="destroyable" name="destroyable" placeholder="Enter Destroyable Qty" value="{{ $astb->destroyable }}">
                                </div>
                            </div>  
                        </div>
                    </fieldset>
                    <fieldset class="border rounded-3 p-3">
                        <legend class="float-none w-auto px-3" >Deprecation</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="dep_per" class="form-label">Dep %</label>
                                    <input class="form-control" type="number" id="dep_per" name="dep_per" placeholder="Deprecation %" value="{{ $astb->dep_per }}">
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="dep_amt" class="form-label">Deprecation Amount</label>
                                    <input class="form-control" type="number" step="0.01" id="dep_amt" name="dep_amt" placeholder="Deprecation Amount" value="{{ $astb->dep_amt }}">
                                </div>
                            </div>  
                        </div>
                    </fieldset>
                    <fieldset class="border rounded-3 p-3">
                        <legend class="float-none w-auto px-3" >Amounts</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="amt_after_depr" class="form-label">Amount After Depr</label>
                                    <input class="form-control" type="number" step="0.01" id="amt_after_depr" name="amt_after_depr" placeholder="Enter Repairable Qty" value="{{ $astb->amt_after_depr }}" readonly>
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="unsv_items_amt" class="form-label">Cost of UNSV Items</label>
                                    <input class="form-control" type="number" step="0.01" id="unsv_items_amt" name="unsv_items_amt" placeholder="Enter Auction Qty" value="{{ $astb->unsv_items_amt }}" readonly>
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="pre_value" class="form-label">Presrent Value</label>
                                    <input class="form-control" type="number" step="0.01" id="pre_value" name="pre_value" placeholder="Enter Destroyable Qty" value="{{ $astb->pre_value }}" readonly>
                                </div>
                            </div>  
                        </div>
                    </fieldset>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="date" class="form-label">R by the BOO</label>
                            <select class="form-control" name="rboo" id="rboo">
                                <option value="">Select One Option</option>
                                <option value="to be destroy" @if($astb->rboo=='to be destroy') selected @endif>To be destroy</option>
                                <option value="to be auction" @if($astb->rboo=='to be auction') selected @endif>To be auction</option>
                            </select>
                        </div>
                    </div> 
                </div>
            <div class="mt-2">
                <button class="btn btn-primary w-100" id="submitBtn" type="submit">Update ASTB</button>
            </div>
        </form>         
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>



<script>
// $(document).on('input', '.qty, .rate', function() {
//     var qty = parseFloat($('.qty').val()) || 0; 
//     var rate = parseFloat($('.rate').val()) || 0; 
//     var amt = qty * rate;
//     $('.amt').val(amt.toFixed(2)); 
// });

// $(document).on('input', '#repairable,#auction,#destroyable', function() {
//     //UNSV Amount rate*repairable
//     var repairable = parseFloat($(this).val()) || 0; 
//     var rate = parseFloat($('.rate').val()) || 0; 
//     var unsv_items_amt = repairable * rate;
//     $('#unsv_items_amt').val(unsv_items_amt.toFixed(2)); 

//     //present amount
//     var amt_after_depr = $('#amt_after_depr').val();
//     var pre_value =  amt_after_depr - unsv_items_amt;
//     $('#pre_value').val(pre_value.toFixed(2))
// });

// $(document).on('input', '#dep_per', function() {
//     var amt = parseFloat($('#amt').val()) || 0; 
//     var dep_per = parseFloat($('#dep_per').val()) || 0; 
//     var dep_amt = (amt * dep_per)/100;
//     $('#dep_amt').val(dep_amt.toFixed(2)); 

//     var amt_after_depr = amt-dep_amt;
//     $('#amt_after_depr').val(amt_after_depr.toFixed(2)); 

//     var pre_value =  amt_after_depr - $('#unsv_items_amt').val();
//     $('#pre_value').val(pre_value.toFixed(2))
// });

// function calculateSum() {
//     let sum = 0;
//     $('.qty').each(function() {
//         const value = parseFloat($(this).val());
//         if (!isNaN(value)) {
//                 sum += value; 
//             }
//     });
//     $('#toatlqty').text(sum);
// }
// $(document).on('input', '.qty', calculateSum);
// calculateSum();
</script>
@endsection