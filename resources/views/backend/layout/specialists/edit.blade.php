@extends('backend.master')

@section('title', 'Edit Specialist')

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
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Edit Specialist</h5>
                        <p class="text-muted mb-0 fs-12">Update specialist details, specialties, and available slots</p>
                    </div>
                    <a href="{{ route('backend.specialist.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('backend.specialist.update', $specialist->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $specialist->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Title / Designation <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $specialist->title) }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $specialist->email) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $specialist->phone) }}">
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
                                    @php
                                        $currentSpecialties = $specialist->specialties ?? [];
                                    @endphp
                                    @foreach($currentSpecialties as $idx => $spec)
                                        <div class="specialty-pill-wrapper me-1 mb-1">
                                            <input type="checkbox" name="specialties[]" value="{{ $spec }}" id="spec_{{ $idx }}" class="specialty-pill-checkbox" checked>
                                            <label for="spec_{{ $idx }}" class="specialty-pill-label">{{ $spec }}</label>
                                            <span class="remove-specialty-pill" title="Remove Tag" onclick="removeSpecialtyPill(this)">&times;</span>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted mt-1 d-block">Click <strong>&times;</strong> to remove any tag. Use <strong>+ Add Specialty</strong> button to add new tags.</small>
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
                                        $currentSlots = $specialist->available_slots ?? ['12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM'];
                                    @endphp
                                    @foreach($currentSlots as $sIdx => $time)
                                        <div class="slot-pill-wrapper me-1 mb-1">
                                            <input type="checkbox" name="available_slots[]" value="{{ $time }}" id="slot_{{ $sIdx }}" class="slot-pill-checkbox" checked>
                                            <label for="slot_{{ $sIdx }}" class="slot-pill-label">{{ $time }}</label>
                                            <span class="remove-slot-pill" title="Remove Slot" onclick="removeSlotPill(this)">&times;</span>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted mt-1 d-block">Click <strong>&times;</strong> to remove any slot. Use <strong>+ Add Time Slot</strong> button to add new times.</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Bio / Description</label>
                                <textarea name="bio" class="form-control" rows="3">{{ old('bio', $specialist->bio) }}</textarea>
                            </div>

                            <!-- Profile Photo / Avatar Upload (Dropify) -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">Profile Photo / Avatar</label>
                                <input type="file" name="avatar" class="dropify" data-height="160" data-max-file-size="20M" accept="image/*" @if($specialist->avatar) data-default-file="{{ asset($specialist->avatar) }}" @endif />
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $specialist->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">Active Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="ri-save-line me-1"></i> Update Specialist</button>
                        </div>
                    </form>
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
        });
    </script>
@endpush
