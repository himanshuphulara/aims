@if($currentmonth!='')
<div class="row">
    <div class="col-12">
        <div class="page-title-box text-center fw-bold">
                
                <h4 class="mb-sm-0">
                     {{ $currentmonth }}
                </h4>
                         
        </div>
    </div>
</div>
@endif   
<div class="row">
    <div class="col-6">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $title }}
                @if($count>0)
                    ({{$count}})
                @endif
            </h4>
        </div>
    </div>
    
    {{-- @if(auth()->user()->role==1) --}}
    <div class="col-6">
        <div class="page-title-box d-sm-flex align-items-center float-end">
            @if($button!='')
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#{{ $target }}"><i class="las la-plus me-1"></i> {{ $button }}</button>
            @endif
        </div>
    </div>
    {{-- @endif --}}
</div>