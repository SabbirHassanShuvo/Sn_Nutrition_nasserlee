@extends('backend.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mb-4">Create Role</h4>
                <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('backend.role.index') }}" class="btn btn-danger">Back</a>
                    </div>  
                <form action="{{ route('backend.role.update', @$role->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    {{-- Role Name --}}
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name</label>
                        <input type="text" id="roleName"
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ $role->name }}" disabled>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div class="mb-3">
                        <label class="form-label">Assign Permissions</label>

                        {{-- Check All --}}
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                            <label class="form-check-label fw-bold" for="checkAll">Check All</label>
                        </div>

                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-3 col-sm-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="form-check-input permission-checkbox"
                                            id="perm-{{ $permission->name }}"
                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $permission->name }}">
                                            {{ ucfirst($permission->name) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Save Role</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts-bottom')
<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush