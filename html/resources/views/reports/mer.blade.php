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
            
            <!-- Date Filter Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('mer-report') }}" class="row align-items-end">
                                <div class="col-md-3">
                                    <label for="category_id" class="form-label">Fund Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        @foreach($allCategories as $category)
                                            <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="year" class="form-label">Financial Year</label>
                                    <select name="year" id="year" class="form-control">
                                        @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                            <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="month" class="form-label">Month</label>
                                    <select name="month" id="month" class="form-control">
                                        @php
                                            $months = [
                                                '04' => 'April', '05' => 'May', '06' => 'June',
                                                '07' => 'July', '08' => 'August', '09' => 'September',
                                                '10' => 'October', '11' => 'November', '12' => 'December',
                                                '01' => 'January', '02' => 'February', '03' => 'March'
                                            ];
                                        @endphp
                                        @foreach($months as $num => $name)
                                            <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="las la-search me-1"></i>Generate Report
                                    </button>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                        <i class="las la-file-excel me-1"></i>Export to Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MER Report Table -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Monthly Expenditure Return (MER) - {{ $publicCategory->name }}</h4>
                            <p class="text-muted mb-0">Financial Year {{ $currentYear }} | Unit: {{ $unitName ?? 'ALL UNITS' }}</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" id="mer-table">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">Ser</th>
                                            <th rowspan="2" class="text-center align-middle">Unit</th>
                                            <th rowspan="2" class="text-center align-middle">Budget Head</th>
                                            <th rowspan="2" class="text-center align-middle">Total Allotment<br>(Rs.)</th>
                                            <th colspan="4" class="text-center">Expenditure</th>
                                            <th colspan="3" class="text-center">PCDA Transactions</th>
                                            <th rowspan="2" class="text-center align-middle">Balance<br>(Rs.)</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Expdr upto<br>Previous Month<br>(Rs.)</th>
                                            <th class="text-center">Expdr during<br>the Month<br>(Rs.)</th>
                                            <th class="text-center">Total Expdr<br>(Rs.)</th>
                                            <th class="text-center">% of Expdr</th>
                                            <th class="text-center">Bills Fwd<br>to PCDA<br>(Rs.)</th>
                                            <th class="text-center">Amount Booked<br>by PCDA<br>(Rs.)</th>
                                            <th class="text-center">% of Booking</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($merData as $row)
                                        <tr>
                                            <td class="text-center">{{ $row['ser'] }}</td>
                                            <td>{{ $row['unit'] }}</td>
                                            <td>{{ $row['budget_head'] }}</td>
                                            <td class="text-end">{{ number_format($row['total_allotment'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['expd_upto_prev'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['expd_during_month'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['total_expd'], 2) }}</td>
                                            <td class="text-end">{{ $row['percentage_expd'] }}%</td>
                                            <td class="text-end">{{ number_format($row['bills_fwd_pcda'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['amt_booked_pcda'], 2) }}</td>
                                            <td class="text-end">{{ $row['percentage_booking'] }}%</td>
                                            <td class="text-end {{ $row['balance'] < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                {{ number_format($row['balance'], 2) }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted">
                                                <i class="las la-info-circle"></i> No data available for {{ $publicCategory->name }} category
                                            </td>
                                        </tr>
                                        @endforelse
                                        
                                        @if(count($merData) > 0)
                                        <tr class="table-info fw-bold">
                                            <td colspan="3" class="text-center">TOTAL</td>
                                            <td class="text-end">{{ number_format(collect($merData)->sum('total_allotment'), 2) }}</td>
                                            <td class="text-end">{{ number_format(collect($merData)->sum('expd_upto_prev'), 2) }}</td>
                                            <td class="text-end">{{ number_format(collect($merData)->sum('expd_during_month'), 2) }}</td>
                                            <td class="text-end">{{ number_format(collect($merData)->sum('total_expd'), 2) }}</td>
                                            <td class="text-end">
                                                {{ collect($merData)->sum('total_allotment') > 0 ? round((collect($merData)->sum('total_expd') / collect($merData)->sum('total_allotment')) * 100, 2) : 0 }}%
                                            </td>
                                            <td class="text-end">{{ number_format(collect($merData)->sum('bills_fwd_pcda'), 2) }}</td>
                                            <td class="text-end">{{ number_format(collect($merData)->sum('amt_booked_pcda'), 2) }}</td>
                                            <td class="text-end">
                                                {{ collect($merData)->sum('bills_fwd_pcda') > 0 ? round((collect($merData)->sum('amt_booked_pcda') / collect($merData)->sum('bills_fwd_pcda')) * 100, 2) : 0 }}%
                                            </td>
                                            <td class="text-end {{ collect($merData)->sum('balance') < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format(collect($merData)->sum('balance'), 2) }}
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToExcel() {
    // Simple table export to Excel
    var table = document.getElementById('mer-table');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + escape(html);
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = "MER_Report_{{ date('Y_m', strtotime("$currentYear-$currentMonth-01")) }}.xls";
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

@endsection