@extends('layouts.master')
@section('maincontent')
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="main-content">
    <style>
         /* .dataTables_filter {
            float: left !important; 
            margin-right: 20px !important; 
        } */
        .dataTables_filter input {
            width: 200px !important; 
        }
        th.sorting:after, th.sorting_asc:after, th.sorting_desc:after,th.sorting:before, th.sorting_asc:before, th.sorting_desc:before {
            display: none !important;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <x-bar-after-top-bar title="{{ $title }}" button="" count="0" target="addcrv" currentmonth="CRV LEDGER OF CURRENT YEAR ({{ now()->format('Y') }})"/>
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
                <div class="col-xs-6 ">
                    <div class="page-title-box b-0 m-b-0">
                        <div class="btn-group dash-btn-group one-for-all pull-right ndays new-ndays dnone">
                            <button type="button" class="btn btn-primary waves-effect text-bold active" id="td">TODAY</button>
                            <button type="button" class="btn btn-primary waves-effect text-bold" id="mtd">MTD</button>
                            <button type="button" class="btn btn-primary waves-effect text-bold" id="sixty">60</button>
                            <button type="button" class="btn btn-primary waves-effect text-bold" id="ytd">YTD</button>
                            <button type="button" class="btn btn-primary waves-effect text-bold" id="custmDateGraph">Custom</button>
                        </div>
                        
                        <button class="btn btn-primary float-end" ledger="crv-ledger" id="btnExport" >
                            <i class="me-1 las la-file-export"></i>Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table  class="table table-bordered table-hover table-nowrap align-middle mb-0" id="grid-selection">
                                    <thead>
                                        {{-- <th data-column-id="lpno">S.No.</th> --}}
                                        <tr>
                                            <th data-column-id="date">Date</th>
                                            <th data-column-id="lpno">LP.No.</th>
                                            <th data-column-id="items">Nomenclature</th>
                                            <th data-column-id="au">A/U</th>
                                            <th data-column-id="qty" class="text-center">Qty</th>
                                            <th data-column-id="rate" class="text-end">Rate</th>
                                            <th data-column-id="amt" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @php $i=1; @endphp
                                        @forelse ($crvledger as $crv)
                                        @php $crvdata = json_decode($crv->crv,true); @endphp
                                            @foreach ($crvdata as $data)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $data['lpno'] }}</td>
                                                <td>{{ $data['items'] }}</td>
                                                <td>{{ $data['au'] }}</td>
                                                <td>{{ $data['date'] }}</td>
                                                <td class="text-center">{{ $data['qty'] }}</td>
                                                <td class="text-end">{{ number_format($data['rate'],2,'.',',') }}</td>
                                                <td class="text-end">{{ number_format($data['qty']*$data['rate'],2,'.',',') }}</td>
                                            </tr>                                                
                                            @endforeach
                                        @empty
                                        <tr class="text-center"><td colspan="25">No Data Founds</td></tr>
                                        @endforelse
                                    </tbody> --}}
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Total</th>
                                            <th id="total"></th>
                                         </tr>
                                    </tfoot>
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

@section('scripts')

{{-- date picker code  --}}
<x-datepicker />

   
<script>
$('#grid-selection').DataTable({
    processing: true,
    serverSide: true,
    "order": [[ 3, "desc" ]],
    language: {
        processing: "Please wait for the response..."
    },
    ajax: {
        url: "{{ route('crvledgerdata', [request()->route()->parameters['vocid']]) }}",
        method: 'GET',
        data: function(d) {
                d.ndays = $(".dash-btn-group.ndays .active").attr("id");
                d.startDate = $("#startDate").val();
                d.endDate = $("#endDate").val();
            }
    },
    columns: [
        { data: 'date', name: 'date' },
        { data: 'lpno', name: 'lpno' },
        { data: 'items', name: 'items' },
        { data: 'au', name: 'au' },
        { data: 'qty', name: 'qty' },
        { data: 'rate', name: 'rate' },
        { data: 'amt', name: 'amt' }
    ],
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    language: { lengthMenu: "Display _MENU_ records per page" },
    columnDefs: [
        {targets: [5, 6], className: 'text-end'},
        {targets: [4], className: 'text-center'},
        {
            "targets": 6,
            "render": function(data, type, item, meta) {
                return ((item.qty)*(item.rate)).toFixed(2);
            }
        }
    ],
    "footerCallback": function (row, data, start, end, display) {
        var totalAmount = 0;
        for (var i = 0; i < data.length; i++) {
            totalAmount += parseFloat(data[i]['amt']);
        }
        $('#total').text(totalAmount.toFixed(2));
    },
    drawCallback: function() {
        $('#grid-selection_paginate .paginate_button').each(function() {
            $(this).removeClass('paginate_button').addClass('btn btn-primary m-1');
            if($(this).hasClass('current')){
                $(this).addClass('active m-1');
            }
        });
    }
});
// $('#grid-selection').removeClass('dataTable');
// $(function () 
// {
//     var table = $('#grid-selection').DataTable();
    
//     $("#btnExport").click(function(e) 
//     {
//     	table.page.len( -1 ).draw();
//         window.open('data:application/vnd.ms-excel,' + 
//         	encodeURIComponent($('#grid-selection').parent().html()));
//       setTimeout(function(){
//       	table.page.len(10).draw();
//       }, 1000)
      
//     });
// });

</script>
{{-- export data component script --}}
@php $columns = ['date','lpno','items','au','qty','rate','amt']; @endphp
<x-export-data :columns="$columns" />
    @endsection