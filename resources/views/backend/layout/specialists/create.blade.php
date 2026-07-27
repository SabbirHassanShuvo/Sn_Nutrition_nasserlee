@extends('backend.master')

@section('title', 'Add Specialist')

@push('styles-top')
    <style>
        /* Specialty Pills Styling */
        .specialty-pill-wrapper {
            position: relative;
            display: inline-block;
        }
        .specialty-pill-checkbox {
            display: none;
        }
        .specialty-pill-label {
            display: inline-block;
            padding: 8px 32px 8px 16px;
            border-radius: 20px;
            background-color: #f3f4f6;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease-in-out;
            user-select: none;
        }
        .specialty-pill-label:hover {
            background-color: #e5e7eb;
        }
        .specialty-pill-checkbox:checked + .specialty-pill-label {
            background-color: #10b981;
            color: #ffffff !important;
            border-color: #059669;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }
        .specialty-pill-checkbox:checked + .specialty-pill-label::before {
            content: "✓ ";
            font-weight: bold;
            color: #ffffff !important;
        }
        .remove-specialty-pill {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            font-weight: bold;
            color: #9ca3af;
            cursor: pointer;
            line-height: 1;
            z-index: 10;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .remove-specialty-pill:hover {
            color: #ffffff !important;
            background-color: #ef4444;
        }
        .specialty-pill-checkbox:checked + .specialty-pill-label + .remove-specialty-pill {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .specialty-pill-checkbox:checked + .specialty-pill-label + .remove-specialty-pill:hover {
            color: #ffffff !important;
            background-color: #dc2626;
        }

        /* Time Slot Pills Styling */
        .slot-pill-wrapper {
            position: relative;
            display: inline-block;
        }
        .slot-pill-checkbox {
            display: none;
        }
        .slot-pill-label {
            display: inline-block;
            padding: 8px 32px 8px 16px;
            border-radius: 20px;
            background-color: #f3f4f6;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease-in-out;
            user-select: none;
        }
        .slot-pill-label:hover {
            background-color: #e5e7eb;
        }
        .slot-pill-checkbox:checked + .slot-pill-label {
            background-color: #2563eb;
            color: #ffffff !important;
            border-color: #1d4ed8;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        }
        .slot-pill-checkbox:checked + .slot-pill-label::before {
            content: "✓ ";
            font-weight: bold;
            color: #ffffff !important;
        }
        .remove-slot-pill {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            font-weight: bold;
            color: #9ca3af;
            cursor: pointer;
            line-height: 1;
            z-index: 10;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .remove-slot-pill:hover {
            color: #ffffff !important;
            background-color: #ef4444;
        }
        .slot-pill-checkbox:checked + .slot-pill-label + .remove-slot-pill {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .slot-pill-checkbox:checked + .slot-pill-label + .remove-slot-pill:hover {
            color: #ffffff !important;
            background-color: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-primary">Add Specialist</h5>
                            <p class="text-muted mb-0 fs-12">Create individual or batch multi-specialists</p>
                        </div>
                        <a href="{{ route('backend.specialist.index') }}" class="btn btn-light btn-sm">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                    </div>
                    
                    <!-- Navigation Tabs: Single vs Multi Specialist Creation -->
                    <ul class="nav nav-tabs nav-tabs-custom card-header-tabs border-bottom-0 mt-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#singleSpecialistTab" role="tab">
                                <i class="ri-user-add-line me-1"></i> Single Specialist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#multiSpecialistTab" role="tab">
                                <i class="ri-user-shared-line me-1"></i> Multi-Specialist Batch Create (Multiple)
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab 1: Single Specialist -->
                        <div class="tab-pane active" id="singleSpecialistTab" role="tabpanel">
                            <form action="{{ route('backend.specialist.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Dr. Karim Benali" required value="{{ old('name') }}">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Title / Designation <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Clinical Nutritionist" required value="{{ old('title') }}">
                                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="e.g. karim@example.com" value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="e.g. +123456789" value="{{ old('phone') }}">
                                    </div>
                                    
                                    <!-- Dynamic User-Friendly Specialties (Tags) with Remove Option & + Add Specialty Modal Button -->
                                    <div class="col-md-12 mb-4">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label fw-semibold mb-0">Specialties (Tags) <span class="text-danger">*</span></label>
                                            <button type="button" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#addSpecialtyModal">
                                                <i class="ri-add-line align-bottom me-1"></i> Add Specialty
                                            </button>
                                        </div>
                                        
                                        <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded" id="specialtiesContainer">
                                            @foreach($specialtiesList as $idx => $spec)
                                                <div class="specialty-pill-wrapper me-1 mb-1">
                                                    <input type="checkbox" name="specialties[]" value="{{ $spec }}" id="spec_{{ $idx }}" class="specialty-pill-checkbox" {{ $loop->first || $loop->iteration == 2 ? 'checked' : '' }}>
                                                    <label for="spec_{{ $idx }}" class="specialty-pill-label">{{ $spec }}</label>
                                                    <span class="remove-specialty-pill" title="Remove Tag" onclick="removeSpecialtyPill(this)">&times;</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted mt-1 d-block">Click tag to select/unselect. Click <strong>&times;</strong> to remove tag. Use <strong>+ Add Specialty</strong> button to create new tags.</small>
                                        @error('specialties') <div class="text-danger fs-12 mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Available Time Slots with Add / Remove Options & Native Time Picker Modal -->
                                    <div class="col-md-12 mb-4">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <label class="form-label fw-semibold mb-0">Available Time Slots</label>
                                            <button type="button" class="btn btn-sm btn-soft-info" data-bs-toggle="modal" data-bs-target="#addSlotModal">
                                                <i class="ri-time-line align-bottom me-1"></i> Add Time Slot
                                            </button>
                                        </div>
                                        
                                        <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded" id="slotsContainer">
                                            @php
                                                $defaultTimes = ['12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];
                                            @endphp
                                            @foreach($defaultTimes as $sIdx => $time)
                                                <div class="slot-pill-wrapper me-1 mb-1">
                                                    <input type="checkbox" name="available_slots[]" value="{{ $time }}" id="slot_{{ $sIdx }}" class="slot-pill-checkbox" checked>
                                                    <label for="slot_{{ $sIdx }}" class="slot-pill-label">{{ $time }}</label>
                                                    <span class="remove-slot-pill" title="Remove Slot" onclick="removeSlotPill(this)">&times;</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted mt-1 d-block">Click slot to select/unselect. Click <strong>&times;</strong> to remove slot. Use <strong>+ Add Time Slot</strong> button to add new times.</small>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Bio / Description</label>
                                        <textarea name="bio" class="form-control" rows="3" placeholder="Brief details about the specialist..."></textarea>
                                    </div>

                                    <!-- User Friendly Profile Photo Upload (Dropify) -->
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-semibold">Profile Photo / Avatar</label>
                                        <input type="file" name="avatar" class="dropify" data-height="160" data-max-file-size="20M" accept="image/*" />
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                                            <label class="form-check-label fw-semibold" for="is_active">Active Status</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4"><i class="ri-save-line me-1"></i> Save Specialist</button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab 2: Multi Specialist Batch Creation -->
                        <div class="tab-pane" id="multiSpecialistTab" role="tabpanel">
                            <form action="{{ route('backend.specialist.store') }}" method="POST">
                                @csrf
                                <p class="text-muted">Create multiple specialists at once by adding rows below.</p>
                                
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered align-middle" id="multiSpecialistTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 25%;">Name <span class="text-danger">*</span></th>
                                                <th style="width: 20%;">Title</th>
                                                <th style="width: 20%;">Email</th>
                                                <th style="width: 25%;">Specialties (Comma Separated)</th>
                                                <th style="width: 10%; text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="multiRowBody">
                                            <tr>
                                                <td>
                                                    <input type="text" name="multi[0][name]" class="form-control form-control-sm" placeholder="Dr. Karim Benali" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="multi[0][title]" class="form-control form-control-sm" placeholder="Clinical Nutritionist">
                                                </td>
                                                <td>
                                                    <input type="email" name="multi[0][email]" class="form-control form-control-sm" placeholder="karim@example.com">
                                                </td>
                                                <td>
                                                    <input type="text" name="multi[0][specialties]" class="form-control form-control-sm" placeholder="Weight management, Sports nutrition">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-soft-danger remove-row-btn" disabled><i class="ri-delete-bin-line"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="multi[1][name]" class="form-control form-control-sm" placeholder="Dr. Sienna Huang">
                                                </td>
                                                <td>
                                                    <input type="text" name="multi[1][title]" class="form-control form-control-sm" placeholder="Pediatric Dietitian">
                                                </td>
                                                <td>
                                                    <input type="email" name="multi[1][email]" class="form-control form-control-sm" placeholder="sienna@example.com">
                                                </td>
                                                <td>
                                                    <input type="text" name="multi[1][specialties]" class="form-control form-control-sm" placeholder="Weight management, Sports nutrition">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-soft-danger remove-row-btn"><i class="ri-delete-bin-line"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-soft-success btn-sm" id="addRowBtn">
                                        <i class="ri-add-line me-1"></i> Add Another Row
                                    </button>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="ri-check-double-line me-1"></i> Create All Specialists
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding New Specialty Tag -->
    <div class="modal fade" id="addSpecialtyModal" tabindex="-1" aria-labelledby="addSpecialtyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="addSpecialtyModalLabel">Add New Specialty</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_specialty_name" class="form-label fw-semibold">Specialty Name <span class="text-danger">*</span></label>
                        <input type="text" id="new_specialty_name" class="form-control" placeholder="e.g. Sports Dietitian, Keto Specialist" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSpecialtyModalBtn">Save Specialty</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding New Time Slot (Native Time Picker) -->
    <div class="modal fade" id="addSlotModal" tabindex="-1" aria-labelledby="addSlotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white" id="addSlotModalLabel">Add Time Slot</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_slot_time" class="form-label fw-semibold">Select Time <span class="text-danger">*</span></label>
                        <input type="time" id="new_slot_time" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info btn-sm text-white" id="saveSlotModalBtn">Add Slot</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        function removeSpecialtyPill(btn) {
            $(btn).closest('.specialty-pill-wrapper').fadeOut(200, function() {
                $(this).remove();
            });
        }

        function removeSlotPill(btn) {
            $(btn).closest('.slot-pill-wrapper').fadeOut(200, function() {
                $(this).remove();
            });
        }

        function formatTimeString(timeStr) {
            if (!timeStr) return '';
            var parts = timeStr.split(':');
            if (parts.length >= 2) {
                var hours = parseInt(parts[0], 10);
                var minutes = parts[1];
                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                return hours + ':' + minutes + ' ' + ampm;
            }
            return timeStr;
        }

        $(document).ready(function() {
            // Initialize Dropify for sleek profile picture upload
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop profile photo or click here',
                    'replace': 'Drag and drop or click to replace photo',
                    'remove':  'Remove',
                    'error':   'Error uploading image.'
                }
            });

            // Handle Add Specialty Modal Save
            $('#saveSpecialtyModalBtn').on('click', function() {
                var name = $('#new_specialty_name').val().trim();
                if (!name) {
                    toastr.error('Please enter a specialty name.');
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('backend.specialist.store-specialty') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name
                    },
                    success: function(res) {
                        btn.prop('disabled', false).text('Save Specialty');
                        $('#new_specialty_name').val('');
                        $('#addSpecialtyModal').modal('hide');

                        var newId = 'spec_dyn_' + Date.now();
                        var newPill = `<div class="specialty-pill-wrapper me-1 mb-1">
                            <input type="checkbox" name="specialties[]" value="${res.name}" id="${newId}" class="specialty-pill-checkbox" checked>
                            <label for="${newId}" class="specialty-pill-label">${res.name}</label>
                            <span class="remove-specialty-pill" title="Remove Tag" onclick="removeSpecialtyPill(this)">&times;</span>
                        </div>`;

                        $('#specialtiesContainer').append(newPill);
                        toastr.success('Specialty tag created and selected!');
                    },
                    error: function() {
                        btn.prop('disabled', false).text('Save Specialty');
                        toastr.error('Failed to create specialty tag.');
                    }
                });
            });

            // Handle Add Time Slot Modal Save with Time Picker
            $('#saveSlotModalBtn').on('click', function() {
                var rawTime = $('#new_slot_time').val();
                if (!rawTime) {
                    toastr.error('Please select a time.');
                    return;
                }
                var timeVal = formatTimeString(rawTime);

                var newId = 'slot_dyn_' + Date.now();
                var newPill = `<div class="slot-pill-wrapper me-1 mb-1">
                    <input type="checkbox" name="available_slots[]" value="${timeVal}" id="${newId}" class="slot-pill-checkbox" checked>
                    <label for="${newId}" class="slot-pill-label">${timeVal}</label>
                    <span class="remove-slot-pill" title="Remove Slot" onclick="removeSlotPill(this)">&times;</span>
                </div>`;

                $('#slotsContainer').append(newPill);
                $('#new_slot_time').val('');
                $('#addSlotModal').modal('hide');
                toastr.success('Time slot added successfully!');
            });

            // Multi specialist table row addition
            var rowIdx = 2;
            $('#addRowBtn').on('click', function() {
                var newRow = `<tr>
                    <td><input type="text" name="multi[${rowIdx}][name]" class="form-control form-control-sm" placeholder="Specialist Name" required></td>
                    <td><input type="text" name="multi[${rowIdx}][title]" class="form-control form-control-sm" placeholder="Title / Role"></td>
                    <td><input type="email" name="multi[${rowIdx}][email]" class="form-control form-control-sm" placeholder="Email"></td>
                    <td><input type="text" name="multi[${rowIdx}][specialties]" class="form-control form-control-sm" placeholder="Specialties tag1, tag2"></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-soft-danger remove-row-btn"><i class="ri-delete-bin-line"></i></button></td>
                </tr>`;
                $('#multiRowBody').append(newRow);
                rowIdx++;
            });

            $(document).on('click', '.remove-row-btn', function() {
                if ($('#multiRowBody tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
@endpush
