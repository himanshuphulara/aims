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
            <x-bar-after-top-bar title="{{ $title }}" button="" count="{{ count($crvledger) }}" target="addcrv" currentmonth="LEDGER MONTH ({{ now()->format('F') }})"/>
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
                    <form action="{{ route('crvledger',[request()->route()->parameters['vocid'],$fundfor]) }}">
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
                            <a href="{{ route('crvledger',[request()->route()->parameters['vocid'],$fundfor]) }}" class="btn btn-primary float-end btn-sm mt-4">
                                <i class="las la-redo-alt"></i>
                            </a>                                                    
                        </div>                                                     
                        </form>  
                        
                        <form action="{{ route('crvledgerdatadownload',[request()->route()->parameters['vocid'],$fundfor]) }}" method="POST" class="float-end">
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
                            <div class="table-responsive table-card" id="tables-container">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th data-column-id="date">S.No.</th>
                                            <th data-column-id="lpno">LP.No.</th>
                                            <th data-column-id="items">Nomenclature</th>
                                            <th data-column-id="au">A/U</th>
                                            <th data-column-id="date" class="text-center">Date</th>
                                            <th data-column-id="qty" class="text-center">Qty</th>
                                            <th data-column-id="rate" class="text-end">Rate</th>
                                            <th data-column-id="amt" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; @endphp
                                        @forelse ($crvledger as $crv)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $crv['lpno'] }}</td>
                                                <td>{{ $crv['items'] }}</td>
                                                <td>{{ $crv['au'] }}</td>
                                                <td class="text-center">{{ $crv['date'] }}</td>
                                                <td class="text-center">{{ $crv['qty'] }}</td>
                                                <td class="text-end">{{ number_format($crv['rate'],2) }}</td>
                                                <td class="text-end">{{ number_format($crv['qty']*$crv['rate'],2) }}</td>
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
</div>

@endsection



{{-- export data component script --}}