
        @extends('layouts.master')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        @section('maincontent')
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">{{ $title }}</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        {{-- Left Side --}}
                        <div class="col-xl-8">
                            <div class="card dash-mini">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">User: {{ ucfirst(auth()->user()->name) }}</h4>
                                </div><!-- end card header -->
                                <div class="col-xl-12">        
                                    <div class="card-body pt-1">
                                        <div class="row">
                                            <div class="col-lg-6 mini-widget pb-3 pb-lg-0">
                                                <div class="d-flex align-items-end">
                                                    <div class="flex-grow-1">
                                                        <h2 class="mb-0 fs-24"><span class="counter-value" data-target="197">2</span></h2>
                                                        <h5 class="text-muted fs-16 mt-2 mb-0">Users Added</h5>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-lg-6 mini-widget py-3 py-lg-0">
                                                <div class="d-flex align-items-end">
                                                    <div class="flex-grow-1">
                                                        <h2 class="mb-0 fs-24"><span class="counter-value" data-target="634">6</span></h2>
                                                        <h5 class="text-muted fs-16 mt-2 mb-0">Fund Types</h5>
                                                    </div>
                                                </div>
                                            </div>
    
                                            {{-- <div class="col-lg-4 mini-widget pt-3 pt-lg-0">
                                                <div class="d-flex align-items-end">
                                                    <div class="flex-grow-1">
                                                        <h2 class="mb-0 fs-24"><span class="counter-value" data-target="512">214</span></h2>
                                                        <h5 class="text-muted fs-16 mt-2 mb-0">Invoice Sent</h5>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Funds Activity ({{ date('Y') }})</h4>
                                    <script>
                                        window.assets = @json($assets);
                                        window.liabilities = @json($liabilities);
                                    </script>
                                </div>
                                <div class="card-body py-1">
                                    <div class="row gy-2">
                                        {{-- <div class="col-md-4">
                                            <h4 class="fs-22 mb-0">₹23,590.00</h4>
                                        </div> --}}
                                        {{-- <div class="col-md-8">
                                            <div class="d-flex main-chart justify-content-end">
                                                <div class="px-4 border-end">
                                                    <h4 class="text-primary fs-22 mb-0">₹584k <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Debit</span></h4>
                                                </div>
                                                <div class="ps-4">
                                                    <h4 class="text-primary fs-22 mb-0">₹324k <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Credit</span></h4>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>

                                    <div id="stacked-column-chart" class="apex-charts" data-colors='["--in-primary", "--in-danger"]' dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        {{-- Right Side --}}

                        <div class="col-xl-4">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="card-title mb-2 text-truncate">Record ({{ date('Y') }})</h5>
                                                <script>
                                                    window.yearly_assets = @json($yearly_assets);
                                                    window.yearly_liabilities = @json($yearly_liabilities);
                                                    window.pieNetKey = @json($pieNetKey);
                                                    window.pieNetValue = @json($pieNetValue);
                                                    // console.log(window.pieNetKey)
                                                    // console.log(window.pieNetValue)
                                                </script>
                                            </div>
                                        </div>

                                        <div id="structure-widget" data-colors='["--in-primary","--in-secondary","--in-info","--in-danger","--in-dark","--in-success"]' class="apex-charts" dir="ltr" style="height:420px !important"></div> 

                                        <div class="px-2">
                                            @foreach($pies as $net)
                                            <div class="structure-list d-flex justify-content-between border-bottom">
                                                <p class="mb-0"><i class="las la-dot-circle fs-18 text-primary me-2"></i>{{ $net->name }}</p>
                                                <div>
                                                    <span class="pe-2 pe-sm-5">₹ {{ $net->book_amount }}</span>
                                                    {{-- <span class="badge bg-primary"> + 0.2% </span> --}}
                                                </div>
                                            </div>
                                            @endforeach
                                            {{-- <div class="structure-list d-flex justify-content-between border-bottom">
                                                <p class="mb-0"><i class="las la-dot-circle fs-18 text-primary me-2"></i>Liabilities</p>
                                                <div>
                                                    <span class="pe-2 pe-sm-5">₹{{ $yearly_liabilities }}</span>
                                                    <span class="badge bg-primary"> - 0.7% </span> 
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <!-- Public Fund Financial Summary Chart -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Public Fund - Financial Summary ({{ date('Y') }})</h4>
                                </div>
                                <div class="card-body">
                                    <div id="public-fund-chart" class="apex-charts" data-colors='["--in-primary", "--in-success", "--in-warning"]' dir="ltr"></div>
                                    <script>
                                        window.publicFundData = @json($publicFundData);
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($categories as $cat)
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-header border-0 align-items-center d-flex rounded-top" style="background:{{ $colorCat[$cat->name] }}">
                                    <h4 class="card-title text-white">{{ strtoupper($cat->name) }}</h4>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="table-responsive table-card">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr class="text-muted text-uppercase">
                                                    <th scope="col">Data By</th>
                                                    <th scope="col" class="text-center">Assets</th>
                                                    <th scope="col" class="text-center">Liabilities</th>
                                                </tr>
                                            </thead>        
                                            <tbody>        
                                                <tr>
                                                    <td>This Month</td>
                                                    <td class="text-center">{{ $monthwise[$cat->name]['assets']??0 }}</td>
                                                    <td class="text-center">{{ $monthwise[$cat->name]['liabilities']??0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Three Month</td>
                                                    <td class="text-center">{{ $threemonthwise[$cat->name]['assets']??0 }}</td>
                                                    <td class="text-center">{{ $threemonthwise[$cat->name]['liabilities']??0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Yearly</td>
                                                    <td class="text-center">{{ $yearwise[$cat->name]['assets']??0 }}</td>
                                                    <td class="text-center">{{ $yearwise[$cat->name]['liabilities']??0 }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

           @endsection