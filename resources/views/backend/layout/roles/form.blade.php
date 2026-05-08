@extends('backend.master')

@section('title', isset($role) ? 'Edit Role' : 'Create Role')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ isset($role) ? 'Edit' : 'Create' }} Role</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('backend.role.index') }}">Roles</a></li>
                            <li class="breadcrumb-item active">{{ isset($role) ? 'Edit' : 'Create' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ isset($role) ? route('backend.role.update', $role->id) : route('backend.role.store') }}" method="POST">
            @csrf
            @if(isset($role))
                @method('PUT')
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center">
                                <i class="ri-shield-user-line me-2 text-primary"></i>
                                Role Configuration
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name ?? '') }}" placeholder="e.g. Manager" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <p class="text-muted fs-12 mt-1">Provide a unique name for this role to identify it in the system.</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold d-block mb-3">Assign Permissions</label>
                                
                                <div class="row">
                                    @foreach($permissions as $group => $items)
                                        <div class="col-md-6 col-xl-4 mb-4">
                                            <div class="card border bg-light h-100 mb-0">
                                                <div class="card-header bg-soft-primary border-bottom py-2 d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 fs-13 fw-bold text-uppercase text-primary">{{ ucfirst($group) }} Management</h6>
                                                    <div class="form-check form-check-inline me-0">
                                                        <input class="form-check-input select-group" type="checkbox" data-group="{{ $group }}">
                                                        <label class="form-check-label fs-11 text-muted">All</label>
                                                    </div>
                                                </div>
                                                <div class="card-body py-3">
                                                    @foreach($items as $permission)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input permission-checkbox group-{{ $group }}" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->name }}" 
                                                                   id="perm_{{ $permission->id }}"
                                                                   {{ (isset($rolePermissions) && in_array($permission->name, $rolePermissions)) ? 'checked' : '' }}>
                                                            <label class="form-check-label fs-13" for="perm_{{ $permission->id }}">
                                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('backend.role.index') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow">
                                    <i class="ri-save-line me-1"></i> {{ isset($role) ? 'Update Role' : 'Create Role' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {
            // Select/Deselect group
            $('.select-group').on('change', function() {
                let group = $(this).data('group');
                let isChecked = $(this).prop('checked');
                $(`.group-${group}`).prop('checked', isChecked);
            });

            // Update "All" checkbox on individual change
            $('.permission-checkbox').on('change', function() {
                let groupClass = Array.from(this.classList).find(c => c.startsWith('group-'));
                let group = groupClass.replace('group-', '');
                let total = $(`.${groupClass}`).length;
                let checked = $(`.${groupClass}:checked`).length;
                $(`.select-group[data-group="${group}"]`).prop('checked', total === checked);
            });

            // Trigger change on load to set "All" checkboxes
            $('.select-group').each(function() {
                let group = $(this).data('group');
                let total = $(`.group-${group}`).length;
                let checked = $(`.group-${group}:checked`).length;
                if (total === checked && total > 0) {
                    $(this).prop('checked', true);
                }
            });
        });
    </script>
@endpush