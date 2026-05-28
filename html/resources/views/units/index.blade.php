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
            @php 
                $button = (auth()->user()->hasPermissions('unitadd') && $canAdd) ? 'Add Army Unit' : '';
                $count = $unit ? 1 : 0;
            @endphp
            <x-bar-after-top-bar title="{{ $title }}" button="{{ $button }}" count="{{ $count }}" target="addunit" currentmonth=""/>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted text-uppercase">
                                            <th style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th scope="col">Army Unit ID</th>
                                            <th scope="col">Army Unit Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Created Date</th>
                                            @if(auth()->user()->role==1)
                                            <th scope="col" style="width: 15%;">Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($unit)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ids[]" id="check1" value="{{ $unit->id }}">
                                                </div>
                                            </td>
                                            <td><p class="fw-medium mb-0">{{ $unit->id }}</p></td>
                                            <td>{{ $unit->unit_name }}</td>
                                            <td>{{ $unit->description ? Str::limit($unit->description, 50) : 'N/A' }}</td>
                                            <td>
                                                @if($unit->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $unit->created_at->format('Y-m-d H:i') }}</td>
                                            @if(auth()->user()->role==1)
                                            <td>
                                                <a class="btn btn-primary btn-sm edit-item-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editunit" 
                                                   data-id="{{ $unit->id }}" 
                                                   data-name="{{ $unit->unit_name }}" 
                                                   data-description="{{ $unit->description }}"
                                                   data-active="{{ $unit->is_active }}">
                                                    <i class="las la-pen-alt fs-18 align-middle"></i> Update
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @else
                                        <tr><td colspan="7" class="text-center">No Army Unit found. Click "Add Army Unit" to create one.</td></tr>
                                        @endif
                                    </tbody>
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
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addunit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addUnitModalLabel">Add Army Unit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('units.store') }}" class="auth-input" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="unit_name" class="form-label">Army Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="unit_name" class="form-control" id="unit_name" placeholder="Enter Army Unit Name (e.g., 1st Infantry Battalion, Artillery Regiment)" value="{{ old('unit_name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter Description (Optional)">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-primary w-100" type="submit">Add Army Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="editunit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editUnitModalLabel">Update Army Unit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('units.update') }}" class="auth-input" method="post">
                    @csrf
                    <input type="hidden" name="unit_id" id="edit_unit_id">
                    
                    <div class="mb-3">
                        <label for="edit_unit_name" class="form-label">Army Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="unit_name" class="form-control" id="edit_unit_name" placeholder="Enter Army Unit Name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="edit_description" rows="3" placeholder="Enter Description (Optional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-primary w-100" type="submit">Update Army Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Clear form when add modal is closed
    $('#addunit').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#is_active').prop('checked', true);
    });

    // Handle edit button click
    $('.edit-item-btn').on("click", function(){
        $('#edit_unit_id').val($(this).data('id'));
        $('#edit_unit_name').val($(this).data('name'));
        $('#edit_description').val($(this).data('description'));
        
        // Set checkbox state
        if($(this).data('active') == 1) {
            $('#edit_is_active').prop('checked', true);
        } else {
            $('#edit_is_active').prop('checked', false);
        }
    });

    // Show edit modal only if coming from edit route
    @if(isset($editUnit))
        $(document).ready(function() {
            $('#editunit').modal('show');
            $('#edit_unit_id').val('{{ $editUnit->id }}');
            $('#edit_unit_name').val('{{ $editUnit->unit_name }}');
            $('#edit_description').val('{{ $editUnit->description }}');
            $('#edit_is_active').prop('checked', {{ $editUnit->is_active ? 'true' : 'false' }});
        });
    @endif
</script>

@endsection