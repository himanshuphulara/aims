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
                            <form method="GET" action="{{ route('qab-category-report') }}" class="row align-items-end">
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
                                <div class="col-md-3">
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
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                        <i class="las la-file-excel me-1"></i>Export to Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QAB Category Report Table -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">COMPARTATIVE STATEMENT OF {{ strtoupper($selectedCategory->name) }} ACCTS</h4>
                            <p class="text-muted mb-0">Financial Year {{ $currentYear }} | Quarter {{ $selectedQuarter }}</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" id="qab-category-table">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">Ser<br>No</th>
                                            <th class="text-center">Fund Head</th>
                                            <th class="text-center">
                                                @php
                                                    // Calculate previous quarter details
                                                    if ($selectedQuarter == 1) {
                                                        $prevQuarter = 4;
                                                        $prevYear = $currentYear - 1;
                                                        $prevMonth = 'JAN'; // Q4 ends in January
                                                    } else {
                                                        $prevQuarter = $selectedQuarter - 1;
                                                        $prevYear = $currentYear;
                                                        switch($prevQuarter) {
                                                            case 1: $prevMonth = 'JUN'; break; // Q1 ends in June
                                                            case 2: $prevMonth = 'SEP'; break; // Q2 ends in September
                                                            case 3: $prevMonth = 'DEC'; break; // Q3 ends in December
                                                        }
                                                    }
                                                    
                                                    // Calculate current quarter end month
                                                    switch($selectedQuarter) {
                                                        case 1: $currentMonth = 'JUN'; break;
                                                        case 2: $currentMonth = 'SEP'; break;
                                                        case 3: $currentMonth = 'DEC'; break;
                                                        case 4: $currentMonth = 'MAR'; break;
                                                    }
                                                @endphp
                                                QE {{ $prevMonth }} {{ $prevYear }}
                                            </th>
                                            <th class="text-center">QE {{ $currentMonth }} {{ $currentYear }}</th>
                                            <th class="text-center">Incr/Decr</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($qabCategoryData as $row)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $row['fund_head'] }}</td>
                                            <td class="text-end">{{ number_format($row['previous_balance'], 2) }}</td>
                                            <td class="text-end">{{ number_format($row['current_balance'], 2) }}</td>
                                            <td class="text-end {{ $row['difference'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $row['difference'] >= 0 ? '(+) ' : '(-) ' }}{{ number_format(abs($row['difference']), 2) }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="las la-info-circle"></i> No subcategories available for {{ $selectedCategory->name }}
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
</div>

<script>
function exportToExcel() {
    // Simple table export to Excel
    var table = document.getElementById('qab-category-table');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + escape(html);
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = "QAB_Category_Report_{{ $selectedCategory->name }}_Q{{ $selectedQuarter }}_{{ $currentYear }}.xls";
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

@endsection