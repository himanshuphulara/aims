<div class="app-menu navbar-menu">
    <!-- LOGO -->
    {{-- <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-dark.png" alt="" height="21">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-light.png" alt="" height="21">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div> --}}

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="las la-house-damage"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                @if(auth()->user()->hasPermissions('userslist') || auth()->user()->hasPermissions('roles'))
                <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button" aria-controls="sidebarAuthentication">
                        <i class="las la-cog"></i> <span data-key="t-authentication">Authentication</span>
                    </a>
                    <div class="menu-dropdown collapse" id="sidebarAuthentication">
                        <ul class="nav nav-sm flex-column">
                            @if(auth()->user()->hasPermissions('userslist'))
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ route('userslist') }}">
                                    <i class="las la-users"></i> <span data-key="t-dashboard">Users</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->hasPermissions('roles'))
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ route('roles') }}">
                                    <i class="las la-lock"></i> <span data-key="t-dashboard">Roles</span>
                                </a>
                            </li>
                            @endif
                            {{-- <li class="nav-item">
                                <a class="nav-link menu-link {{ Route::is('permissions') ? 'active' : '' }}" href="{{ route('permissions') }}">
                                    <i class="las la-shield-alt"></i> <span data-key="t-dashboard">Permissions</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                @endif
                @if(auth()->user()->hasPermissions('categorylist'))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('categorylist') }}">
                        <i class="bx bx-category"></i> <span data-key="t-dashboard">Categories</span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermissions('unitslist'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('units.index') ? 'active' : '' }}" href="{{ route('units.index') }}">
                        <i class="las la-cube"></i> <span data-key="t-dashboard">Units</span>
                    </a>
                </li>
                @endif
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#sidebarFundtypes" data-bs-toggle="collapse" role="button"  aria-controls="sidebarFundtypes">
                        <i class="las la-credit-card"></i> <span data-key="t-fundtypes">Funds Type (Receipt)</span>
                    </a>
                    <div class="menu-dropdown collapse" id="sidebarFundtypes">
                        <ul class="nav nav-sm flex-column">
                            @php $cats = \App\Models\Category::where('parent_id',0)->get(); @endphp
                            @foreach ($cats as $c)
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ route('voucher',[$c->id]) }}">
                                    <span data-key="t-dashboard">{{ $c->name }}</span>
                                </a>
                            </li>                                
                            @endforeach
                        </ul>
                    </div>
                </li> --}}
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#creditpayment" data-bs-toggle="collapse" role="button"  aria-controls="creditpayment">
                        <i class="las la-donate"></i> <span data-key="t-fundtypes">Funds Type (Payment)</span>
                    </a>
                    <div class="menu-dropdown collapse" id="creditpayment">
                        <ul class="nav nav-sm flex-column">
                            @php $cats = \App\Models\Category::where('parent_id',0)->get(); @endphp
                            @foreach ($cats as $c)
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ route('voucherp',[$c->id]) }}">
                                    <span data-key="t-dashboard">{{ $c->name }}</span>
                                </a>
                            </li>                                
                            @endforeach
                        </ul>
                    </div>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#creditpayment" data-bs-toggle="collapse" role="button"  aria-controls="creditpayment">
                        <i class="las la-donate"></i> <span data-key="t-fundtypes">Funds</span>
                    </a>
                    <div class="menu-dropdown collapse" id="creditpayment">
                        <ul class="nav nav-sm flex-column">
                            @php $cats = \App\Models\Category::where('parent_id',0)->get(); @endphp
                            @foreach ($cats as $c)
                            <li class="nav-item">
                                <a class="nav-link menu-link parent" href="#{{ strtolower(str_replace(" ","_",$c->name)) }}" data-bs-toggle="collapse" role="button" aria-controls="{{ strtolower(str_replace(" ","_",$c->name)) }}">
                                    <i class="las la-book"></i> <span data-key="t-authentication">{{ $c->name }}</span>
                                </a>
                                <div class="menu-dropdown collapse" id="{{ strtolower(str_replace(" ","_",$c->name)) }}">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            @if($c->name=='JCO MESS' || $c->name=='Officers Mess')
                                                <a class="nav-link menu-link" route="officerlist" href="{{ route('officerlist',[$c->id]) }}">
                                                    <span data-key="t-dashboard">Officers Record</span>
                                                </a>
                                                <a class="nav-link menu-link" route="messbilllist" href="{{ route('messbilllist',[$c->id]) }}">
                                                    <span data-key="t-dashboard">Mess Bill Summary</span>
                                                </a>
                                            @endif
                                            <a class="nav-link menu-link" route="voucher" href="{{ route('voucher',[$c->id]) }}">
                                                <span data-key="t-dashboard">Credit</span>
                                            </a>
                                            <a class="nav-link menu-link" route="voucherp" href="{{ route('voucherp',[$c->id]) }}">
                                                <span data-key="t-dashboard">Debit</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>                                
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#inventory" data-bs-toggle="collapse" role="button" aria-controls="inventory">
                        <i class="las la-warehouse"></i> <span data-key="t-authentication">Inventory Management</span>
                    </a>
                    <div class="menu-dropdown collapse" id="inventory">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link menu-link parent" href="#ledger" data-bs-toggle="collapse" role="button" aria-controls="ledger">
                                    <i class="las la-book"></i> <span data-key="t-authentication">Ledger</span>
                                </a>
                                <div class="menu-dropdown collapse" id="ledger">
                                    <ul class="nav nav-sm flex-column">
                                        @php $cats = \App\Models\Category::where('parent_id',11)->get(); @endphp
                                        @foreach ($cats as $c)
                                        <li class="nav-item">
                                            <a class="nav-link menu-link" route="{{ $c->name }}" href="{{ route('fundvouchers',[$c->id]) }}">
                                                <span data-key="t-dashboard">{{ $c->name }}</span>
                                            </a>
                                        </li>
                                        @endforeach
                                        
                                        @php $cats = \App\Models\Category::where('parent_id',0)->get(); @endphp
                                        @foreach ($cats as $c)
                                        <li class="nav-item">
                                            <a class="nav-link menu-link" route="{{ $c->name }}" href="{{ route('fundvouchers',[$c->id]) }}">
                                                <span data-key="t-dashboard">{{ $c->name }}</span>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link parent" href="#vouchers" data-bs-toggle="collapse" role="button" aria-controls="vouchers">
                                    <i class="lab la-wpforms"></i> <span data-key="t-authentication">Vouchers</span>
                                </a>
                                <div class="menu-dropdown collapse" id="vouchers">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link" route="crvvouchers" href="{{ route('crvvouchers') }}">
                                                <span data-key="t-dashboard">CRV</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link menu-link" route="nivvouchers" href="{{ route('nivvouchers') }}">
                                                <span data-key="t-dashboard">NIV</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link menu-link" route="civvouchers" href="{{ route('civvouchers') }}">
                                                <span data-key="t-dashboard">CIV</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#reports" data-bs-toggle="collapse" role="button" aria-controls="reports">
                        <i class="las la-chart-bar"></i> <span data-key="t-authentication">Reports</span>
                    </a>
                    <div class="menu-dropdown collapse" id="reports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="mer-report" href="{{ route('mer-report') }}">
                                    <span data-key="t-dashboard">Monthly Expenditure Return (MER)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="qab-report" href="{{ route('qab-report') }}">
                                    <span data-key="t-dashboard">Quarterly Audit Board (QAB)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="qab-category-report" href="{{ route('qab-category-report') }}">
                                    <span data-key="t-dashboard">QAB Category Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if(auth()->user()->hasPermissions('ai.documents.manage') || auth()->user()->hasPermissions('ai.ask') || auth()->user()->hasPermissions('ai.surveys.manage') || auth()->user()->hasPermissions('ai.feedback.view'))
                <li class="nav-item">
                    <a class="nav-link menu-link parent" href="#aiKnowledge" data-bs-toggle="collapse" role="button" aria-controls="aiKnowledge">
                        <i class="las la-robot"></i> <span data-key="t-ai-knowledge">AI Knowledge</span>
                    </a>
                    <div class="menu-dropdown collapse" id="aiKnowledge">
                        <ul class="nav nav-sm flex-column">
                            @if(auth()->user()->hasPermissions('ai.documents.manage'))
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="ai.documents" href="{{ route('ai.documents') }}">
                                    <span data-key="t-ai-documents">Documents</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->hasPermissions('ai.ask'))
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="ai.ask.page" href="{{ route('ai.ask.page') }}">
                                    <span data-key="t-ai-ask">Ask AI</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->hasPermissions('ai.surveys.manage'))
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="ai.surveys" href="{{ route('ai.surveys') }}">
                                    <span data-key="t-ai-surveys">Surveys</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->hasPermissions('ai.feedback.view'))
                            <li class="nav-item">
                                <a class="nav-link menu-link" route="ai.feedback" href="{{ route('ai.feedback') }}">
                                    <span data-key="t-ai-feedback">Feedback</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
                @if(auth()->user()->role==1)
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('dblist') ? 'active' : '' }}" href="{{ route('dblist') }}">
                        <i class="las la-database"></i> <span data-key="t-dblist">DB Backup</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    {{-- <div class="sidebar-background"></div> --}}
</div>

<script>
    $(document).ready(function() {
    var currentUrl = window.location.href; // Get the current page name
    // console.log(currentUrl)
    $('.menu-link').each(function() {
        if ($(this).attr('href') === currentUrl) {
            $(this).addClass('active');
            $(this).closest('.collapse').addClass('show');
            $(this).closest('.collapse').prev('.parent').addClass('active');
        }
    });
    $('.menu-link').on('click',function(){
        localStorage.setItem("route", $(this).attr('route'));
        href = $(this).closest('.collapse').prev('.parent').attr('href');
        localStorage.setItem("href", href);
    })
    $('.menu-link').each(function() {
        if ($(this).attr('route') === localStorage.getItem('route')) {           
            if($(this).closest('.collapse').prev('.parent').attr('href')===localStorage.getItem('href')){
                // console.log($(this).closest('.collapse').prev('.parent').attr('href'))
                $(this).addClass('active');
                $(this).closest('.collapse').addClass('show');
                $(this).closest('.collapse').prev('.parent').closest('.collapse').addClass('show');
                $(this).closest('.collapse').prev('.parent').closest('.collapse').prev('.parent').addClass('active');
                $(this).closest('.collapse').prev('.parent').addClass('active');
            }
        }
    });
    // console.log(localStorage.getItem('route'));
});
</script>