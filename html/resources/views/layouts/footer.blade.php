 {{-- <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Invoika.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Themesbrand
                            </div>
                        </div>
                    </div>
                </div>
            </footer> --}}
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js')}}"></script>
    {{-- <script src="{{ asset('assets/js/plugins.js')}}"></script> --}}

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- Vector map-->
    <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js')}}"></script>
    <script src="{{ asset('assets/js/table/dataTables.min.js')}}"></script>
    <!-- Dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js')}}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js')}}"></script>
    
    <script>
        toastr.options.closeButton = true;
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                toastr.success('{{ session('success') }}');
                {{ session()->forget('success') }}
            @endif
    
            @if (session('error'))
                toastr.error('{{ session('error') }}');
                {{ session()->forget('error') }}
            @endif
        });
    </script>
    <script>
        function set70(box,key) {            
            $(`input[type="checkbox"].${key}:not(:disabled)`).prop('checked', box.checked);
        }
    </script>
	<script>
        $(document).ready(function(){
            $('input[type="number"]').attr({
                'min': '0',
                'step': '0.01'
            });
        });
    </script>
    
@yield('scripts')
</body>
</html>
