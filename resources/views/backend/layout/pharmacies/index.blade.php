@extends('backend.master')

@section('title', 'Pharmacies Management')

@push('styles-top')
    <style>
        .btn-soft-primary {
            background-color: rgba(64, 81, 137, 0.1) !important;
            color: #405189 !important;
            border: none !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-soft-primary:hover {
            background-color: #405189 !important;
            color: #ffffff !important;
        }
        .btn-soft-primary i, .btn-soft-primary:hover i {
            color: inherit !important;
        }

        .btn-soft-danger {
            background-color: rgba(240, 101, 72, 0.1) !important;
            color: #f06548 !important;
            border: none !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-soft-danger:hover {
            background-color: #f06548 !important;
            color: #ffffff !important;
        }
        .btn-soft-danger i, .btn-soft-danger:hover i {
            color: inherit !important;
        }
    </style>
@endpush

@section('content')
    <!-- Velzon Stat Summary Widgets -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card card-animate border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0 fs-12">Total Pharmacies</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-dark" id="totalPharmaciesCount">0</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="ri-capsule-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-animate border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0 fs-12">Active Pharmacies</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-dark" id="activePharmaciesCount">0</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="ri-checkbox-circle-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-animate border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0 fs-12">With Phone Numbers</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-3">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-dark">100%</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="ri-phone-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="pharmacyList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary"><i class="ri-capsule-line me-1"></i> Nearby Pharmacies Directory</h5>
                        <p class="text-muted mb-0 fs-12">Manage pharmacies, addresses, phone numbers, coordinates, services, and photo listings</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Delete Selected
                        </button>
                        <a href="{{ route('backend.pharmacies.create') }}" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Pharmacy
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 custom-table" id="pharmacyTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th>Pharmacy & Phone</th>
                                    <th>Coordinates (Lat / Lng)</th>
                                    <th>Services & Offer Tags</th>
                                    <th>Rating & Operating Hours</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        $(document.body).ready(function() {
            var table = $('#pharmacyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.pharmacies.index') }}",
                    dataSrc: function(json) {
                        $('#totalPharmaciesCount').text(json.recordsTotal || 0);
                        $('#activePharmaciesCount').text(json.recordsFiltered || json.recordsTotal || 0);
                        return json.data;
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'info', name: 'name' },
                    { data: 'coordinates', name: 'latitude', orderable: false },
                    { data: 'services_tags', name: 'services', orderable: false },
                    { data: 'rating_hours', name: 'rating' },
                    { data: 'status', name: 'is_active', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Check all checkboxes
            $('#checkAll').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteBtn();
            });

            $(document).on('change', '.row-checkbox', function() {
                toggleBulkDeleteBtn();
            });

            function toggleBulkDeleteBtn() {
                var selectedCount = $('.row-checkbox:checked').length;
                if (selectedCount > 0) {
                    $('#bulkDeleteBtn').removeClass('d-none').addClass('d-flex');
                } else {
                    $('#bulkDeleteBtn').addClass('d-none').removeClass('d-flex');
                }
            }

            // Status Toggle
            $(document).on('change', '.status-toggle', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/pharmacies/status') }}/" + id,
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        toastr.success('Pharmacy status updated successfully');
                    },
                    error: function() {
                        toastr.error('Failed to update status');
                    }
                });
            });

            // Single Delete with SweetAlert2
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this pharmacy deletion!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/pharmacies') }}/" + id,
                            type: "DELETE",
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(res) {
                                toastr.success(res.message);
                                table.draw(false);
                                toggleBulkDeleteBtn();
                            },
                            error: function() {
                                toastr.error('Failed to delete pharmacy.');
                            }
                        });
                    }
                });
            });

            // Bulk Delete with SweetAlert2
            $('#bulkDeleteBtn').on('click', function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: 'Delete Selected Pharmacies?',
                    text: "You are about to delete " + selectedIds.length + " pharmacy(s).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete all!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('backend.pharmacies.bulk-destroy') }}",
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}",
                                ids: selectedIds
                            },
                            success: function(res) {
                                toastr.success(res.message);
                                $('#checkAll').prop('checked', false);
                                table.draw(false);
                                toggleBulkDeleteBtn();
                            },
                            error: function() {
                                toastr.error('Failed to bulk delete pharmacies.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
