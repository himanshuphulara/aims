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

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Quarterly Audit Board (QAB) Report</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('qab-report') }}" class="row align-items-end">
                            <div class="col-md-4">
                                <label for="year" class="form-label">Financial Year</label>
                                <select name="year" id="year" class="form-control">
                                    @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="quarter" class="form-label">Quarter</label>
                                <select name="quarter" id="quarter" class="form-control">
                                    @for($q = 1; $q <= 4; $q++)
                                        @php
                                            $disabled = ($currentYear == date('Y') && $q > $currentQuarter) ? 'disabled' : '';
                                            $quarterLabel = '';
                                            switch($q) {
                                                case 1: $quarterLabel = 'Q1 (Apr-Jun)'; break;
                                                case 2: $quarterLabel = 'Q2 (Jul-Sep)'; break;
                                                case 3: $quarterLabel = 'Q3 (Oct-Dec)'; break;
                                                case 4: $quarterLabel = 'Q4 (Jan-Mar)'; break;
                                            }
                                        @endphp
                                        <option value="{{ $q }}" {{ $selectedQuarter == $q ? 'selected' : '' }} {{ $disabled }}>
                                            {{ $quarterLabel }}
                                        </option>
                                    @endfor
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
                            </div>
                        </form>

        <!-- QAB Report Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Quarterly Audit Board (QAB) Report - All Fund Categories</h4>
                        <p class="text-muted mb-0">Financial Year {{ $currentYear }} | Quarter {{ $selectedQuarter }} vs Previous Quarter</p>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" id="qab-table">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th rowspan="2" class="text-center align-middle">SER NO</th>
                                        <th rowspan="2" class="text-center align-middle">NAME OF ACCT</th>
                                        <th colspan="2" class="text-center">
                                            @php
                                                // Calculate previous quarter end month
                                                switch($previousQuarter) {
                                                    case 1: $prevMonth = 'JUN'; break;
                                                    case 2: $prevMonth = 'SEP'; break;
                                                    case 3: $prevMonth = 'DEC'; break;
                                                    case 4: $prevMonth = 'MAR'; break;
                                                }
                                                
                                                // Calculate current quarter end month
                                                switch($selectedQuarter) {
                                                    case 1: $currentMonth = 'JUN'; break;
                                                    case 2: $currentMonth = 'SEP'; break;
                                                    case 3: $currentMonth = 'DEC'; break;
                                                    case 4: $currentMonth = 'MAR'; break;
                                                }
                                            @endphp
                                            QE {{ $prevMonth }} {{ $previousYear }}
                                        </th>
                                        <th colspan="2" class="text-center">POSN AS ON PRESENT<br>QE {{ $currentMonth }} {{ $currentYear }}</th>
                                        <th colspan="2" class="text-center">INCREASE/DECREASE</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">CASH IN<br>HAND</th>
                                        <th class="text-center">CASH IN<br>BANK</th>
                                        <th class="text-center">CASH IN<br>HAND</th>
                                        <th class="text-center">CASH IN<br>BANK</th>
                                        <th class="text-center">CASH IN<br>HAND</th>
                                        <th class="text-center">CASH IN BANK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($qabData as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $row['account_name'] }}</td>
                                        <td class="text-end">{{ number_format($row['previous_cash_hand'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['previous_cash_bank'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['current_cash_hand'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['current_cash_bank'], 2) }}</td>
                                        <td class="text-end {{ $row['diff_cash_hand'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $row['diff_cash_hand'] >= 0 ? '(+) ' : '(-) ' }}{{ number_format(abs($row['diff_cash_hand']), 2) }}
                                        </td>
                                        <td class="text-end {{ $row['diff_cash_bank'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $row['diff_cash_bank'] >= 0 ? '(+) ' : '(-) ' }}{{ number_format(abs($row['diff_cash_bank']), 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="las la-info-circle"></i> No fund categories available for reporting
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToExcel() {
    // Simple table export to Excel matching MER style
    var table = document.getElementById('qab-table');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + escape(html);
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = "QAB_Report_Q{{ $selectedQuarter }}_{{ $currentYear }}.xls";
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

@endsection