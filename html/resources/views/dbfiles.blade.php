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
            {{-- <x-bar-after-top-bar title="" button="" count="" target="" currentmonth=""/> --}}
            <!-- start page title -->
            <div class="row">
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }} </h4> 
                    </div>
                </div>
                @if(auth()->user()->role==1)
                <div class="col-6">
                    <div class="page-title-box d-sm-flex align-items-center float-end">
                        <a href="{{ route('backup') }}" class="btn btn-primary btn-sm"><i class="las la-plus me-1"></i> Create New Backup</a>
                    </div>
                </div>
                @endif
            </div>
            <!-- end page title -->

            
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th scope="col">S.No</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">File Size</th>
                                            <th scope="col">Created At</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>@php $i=1; @endphp
                                        @forelse($backups as $backup)
                                        @php 
                                        $filename = date('l_d_M_Y_h:i:s_A',strtotime($backup->created_at));
                                        $latest = strtotime($latestentry->created_at); 
                                        $other = strtotime($backup->created_at);
                                        if($latest == $other)
                                        {
                                            $check = 'Latest';
                                            $color = 'primary';
                                            $glow = 'spinner-grow spinner-grow-sm text-white';
                                        }else{
                                            $check = 'Old';
                                            $color = 'secondary';
                                            $glow = '';
                                        }
                                        $fileSize = File::size(storage_path()."/app/public/".$backup->file);
                                        if($fileSize>0){
                                            $base = log($fileSize) / log(1024);
                                            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
                                            // $size = round(pow(1024, $base - floor($base)), 2) . $suffixes[floor($base)];
                                            $size = number_format($base,2,'.','') . $suffixes[floor($base)];
                                        }else{
                                            $size = $fileSize;
                                        }
                                        @endphp
                                        <tr @if($backup->downloaded==1) class="table-warning" @endif>
                                            <td><p class="fw-medium mb-0">{{ $i++ }}</p></td>
                                            <td>{{ $backup->name }}</td>
                                            <td><span class="badge bg-{{ $color }}">{{ $size }}</span>  </td>
                                            <td>
                                                {{ date('l, d M Y, h:i:s A',strtotime($backup->created_at)) }}
                                                <span class="btn bg-{{ $color }} btn-sm" disabled>
                                                    <span class="{{ $glow }}"></span>
                                                    <span role="status" class="text-white">{{ $check }}</span>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{-- <a href="{{ asset('storage'.$backup->file) }}" download="{{ $filename }}.sql"><button class="btn btn-sm btn-primary"><i class="las la-file-download"></i> Download</button></a> --}}
                                                <a href="{{ route('download',[$backup->id]) }}"><button class="btn btn-sm btn-primary"><i class="las la-file-download"></i> 
                                                    @if($backup->downloaded==1)
                                                    Downloaded
                                                    @else
                                                    Download
                                                    @endif
                                                </button></a>
                                                <a href="{{ route('deletebackup',[$backup->id]) }}"><button class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Delete</button></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Database Backup found</td>
                                        </tr>
                                        @endforelse
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div><!-- end table responsive -->
                        </div>
                    </div>
                </div>
            </div>

                @if(count($backups)>0)
                    {{ $backups->links() }}
                @endif
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
@endsection