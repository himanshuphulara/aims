
@extends('layouts.master')
@section('maincontent')

<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
<style>
    #rolesdiv.rolediv{
        display: flex;
        display: -webkit-flex;
        flex-wrap: wrap;
    }
</style>
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }} </h4>
                    </div>
                </div>
                {{-- <div class="row pb-2">
                    <div class="col-sm-6">
                        <a class="btn btn-primary" href="{{ route('permissions') }}"><i class="las la-plus me-1"></i> Go To Permissisons</a>
                    </div>
                </div> --}}
            </div>
            <!-- end page title -->

            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Set Role Permissions</h5>
                            <input type="checkbox" id="checkAll"  class="form-check-input border-primary"> Check All<br>
                        </div>
                        <div class="card-body">
                            {{-- <div class="table-responsive"> --}}
                            <div class="">
                                @if(count($permissions)>0)
                                <form action="{{ route('rolesetpermissions') }}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ $roleid }}" name="roleid">
                                    @php
                                        $matchedArray = [];
                                        foreach ($permissions as $name => $type) {
                                            $group = $type ?: 'Other';
                                            $matchedArray[$group][] = $name;
                                        }
                                    @endphp
                                <div class="row" id="rolesdiv">
                                    @foreach($matchedArray as $key => $value)
                                <div class="col-md-3 rolediv border border-end-1">
                                    <div class="card-body">
                                        <h6><label for="email" class="form-label text-uppercase"><b>{{ $key }}</b></label></h6>
                                        <input type="checkbox" id="{{$key}}" class="form-check-input border-primary" onchange="set70(this,'{{$key}}')"><br>
                                    @foreach ($value as $key1 => $permission)
                                        <div class="form-check ">                                        
                                            <input class="form-check-input formCheck2 {{$key}} border-primary" type="checkbox" name="permissions[]" value="{{ $permission }}"  @if(in_array($permission,$rolePermissions) || $permission=='dashboard') checked @endif @if($permission=='dashboard') disabled @endif>
                                            <label class="form-check-label" for="formCheck2">
                                                {{ $permission }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-sm w-10" type="submit">Set Permissions</button>
                                </div>
                                </form>
                                @endif
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
<!-- end main content-->

<!-- Modal -->

<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     @if (session('success'))
    //         toastr.success('{{ session('success') }}');
    //     @endif

    //     @if (session('error'))
    //         toastr.error('{{ session('error') }}');
    //     @endif
    // });

    $(document).ready(function() {
        $('#checkAll').click(function() {
        $('input[type="checkbox"]:not(:disabled)').prop('checked', this.checked);

        
    });
    
});
</script>
@endsection