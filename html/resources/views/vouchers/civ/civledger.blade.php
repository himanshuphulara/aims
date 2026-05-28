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
            {{-- cIV LEDGER FOR THE MONTH OF {{ now()->format('M, Y') }} --}}
            <x-bar-after-top-bar title="{{ $title }}" button="" count="0" target="addcrv" currentmonth="SHOWING CIV LEDGER OF CURRENT MONTH ({{ now()->format('F') }})"/>
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
                    <form action="{{ route('civledger',[request()->route()->parameters['vocid']]) }}">
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
                            <a href="{{ route('civledger',[request()->route()->parameters['vocid']]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                <i class="las la-redo-alt"></i>
                            </a>                                                    
                        </div>                                                     
                        </form>                        
                        <form action="{{ route('civledgerdatadownload',[request()->route()->parameters['vocid']]) }}" method="POST" class="float-end">
                                @csrf
                                <input type="date" class="form-control d-none" name="start_date" id="start-date" value="{{ $start }}">                    
                                <input type="date" class="form-control d-none" name="end_date" id="end-date" value="{{ $end }}">
                                <input type="text" class="form-control d-none" name="search" id="search" value="{{ $search }}">                                             
                                <button id="submit" class="btn btn-sm btn-primary mt-4"><i class="me-1 las la-file-export"></i>Download</button>                                                      
                        </form>
                        {{-- <button id="btnExport" class="btn btn-sm btn-primary float-end mt-4" start-date="{{ $start }}" end-date="{{ $end }}" month="Payment (Credit) MONTH OF {{ now()->format('M, Y') }}"><i class="me-1 las la-file-export"></i> ExportTest</button>  --}}
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="grid-selection">
                                    <thead>
                                        <tr>
                                            <th data-column-id="date">S.No</th>
                                            <th data-column-id="date">LPNO</th>
                                            <th data-column-id="items">Nomenclature</th>
                                            <th data-column-id="au" class="text-center">Date</th>
                                            <th data-column-id="qty" class="text-center">Qty</th>
                                            <th data-column-id="remarks" class="text-center">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse ($civledger as $civ)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $civ['lpno'] }}</td>
                                                <td>{{ $civ['items'] }}</td>
                                                <td class="text-center">{{ $civ['date'] }}</td>
                                                <td class="text-center">{{ $civ['qty'] }}</td>
                                                <td class="text-center">{{ $civ['remarks'] }}</td>
                                            </tr>      
                                        @empty
                                        <tr class="text-center"><td colspan="25">No Data Founds</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <input type="hidden" id="startDate" value="">
    <input type="hidden" id="endDate" value="">
</div>
@endsection