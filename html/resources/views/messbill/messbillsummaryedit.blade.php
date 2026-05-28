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
            <x-bar-after-top-bar title="{{ $title }}" button="" count="0" target="" currentmonth=""/>
           
            @php 
                $start = request('start');
                $end = request('end');
                $bills = json_decode($messbill->bill_json,true); 
                $subcatgs_json = json_decode($messbill->subcatgs_json,true);
                $officerlist = \App\Models\Officers::where('id',$messbill->officer_id)->first();
                $forbill=[];
                if(isset($officerlist->subscriptions_json)){
                    foreach($officerlist->subscriptions_json as $key=>$value){
                        $forbill[$key] = $value;
                    }
                } 
                // print_r($forbill);
            @endphp
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="editOfficerForm" action="{{ route('messbillupdate') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <input type="hidden" name="id" value="{{$messbill->id}}" id="mess_id">
                                <input type="hidden" name="category_id" id="officer_cat_id" value="{{$catid}}">
                                <input type="hidden" name="officer_id" id="officer_id" value="{{$messbill->officer_id}}">
                                <input type="hidden" name="start" id="start" value="{{$start}}">
                                <input type="hidden" name="end" id="end" value="{{$end}}">
                                
                                <div class="row">
                                    <div class="col-4 mb-3">
                                        <label for="edit_officer_rank" class="form-label">Officer Rank</label>
                                        <input type="text" name="officer_rank" class="form-control" id="edit_officer_rank" placeholder="Enter Officer Rank" readonly value="{{$messbill->officer_rank}}">
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label for="edit_officer_name" class="form-label">Officer Name</label>
                                        <input type="text" name="officer_name" class="form-control" id="edit_officer_name" placeholder="Enter Officer Name" readonly value="{{$messbill->officer_name}}">
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label for="edit_officer_arrears" class="form-label">Arrears</label>
                                        <input type="number" step="0.01" name="arrears" class="form-control" id="edit_officer_arrears" placeholder="Enter Amount" value="{{$messbill->arrears}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 mb-3">
                                        <label for="edit_officer_no_of_days" class="form-label">No of days</label>
                                        <input type="number" step="0.01" name="no_of_days" class="form-control" id="edit_officer_no_of_days" placeholder="Cheque Date" value="{{$messbill->no_of_days}}">
                                    </div>    
                                    {{-- <div class="col-4 mb-3">
                                        <label for="edit_officer_per_day_messing" class="form-label">Per days messing</label>
                                        <input type="number" step="0.01" name="per_day_messing" class="form-control" id="edit_officer_per_day_messing" placeholder="Enter Cheque Amount" value="{{$messbill->per_day_messing}}">
                                    </div> --}}
                                </div> 
                                <div class="row">
                                    <p>Categories</p>
                                    @php $cat=$catbill=0; @endphp
                                    @foreach ($bills as $key=>$value)
                                    <div class="col-3 mb-3">
                                        <label for="edit_officer_per_day_messing" class="form-label">{{ strtoupper(str_replace("_"," ",$key)) }}</label>
                                        <input type="number" step="0.01" name="catbill[{{$key}}]" id="{{ str_replace(" ","_",$key) }}" class="form-control cate_value"  placeholder="{{ strtoupper(str_replace("_"," ",$key)) }}" value="{{ $value }}" 
                                        @if(isset($forbill[$key]))
                                            readonly
                                        @else
                                        @endif
                                        />
                                    </div>
                                    @if(isset($subcatgs_json[$key]))
                                    @foreach ($subcatgs_json[$key] as $subcat=>$value)
                                    @php $subkey = strtoupper(str_replace("_"," ",$subcat)); @endphp
                                        <div class="col-3 mb-3">
                                            <label for="edit_bill_date" class="form-label">{{ $subkey }}</label>
                                            <input type="number" step="0.01" name="subcatgs[{{ $key }}][{{ $subcat }}]" class="form-control" id="edit_bill_date" placeholder="{{ $subkey }}" value="{{$value}}">
                                        </div>
                                    @endforeach 
                                    @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-3">
                                        <label for="edit_bill_date" class="form-label">Date</label>
                                        <input type="date" name="bill_date" class="form-control" id="edit_bill_date" placeholder="Cheque Date" value="{{$messbill->bill_date}}" required>
                                    </div>
                                    {{-- <div class="col-3 mb-3">
                                        <label for="edit_round_off" class="form-label">Round Off</label>
                                        <input type="number" step="0.01" name="round_off" class="form-control" id="edit_round_off" placeholder="Round Off" value="{{$messbill->round_off}}">
                                    </div> --}}
                                </div> 
                                <button type="submit" class="btn btn-primary w-100 text-uppercase">Update Changes</button>
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
    $('#edit_officer_arrears').on('blur',function(){
        $('#sy_dr').val($('#edit_officer_arrears').val())
    })
</script>
@endsection