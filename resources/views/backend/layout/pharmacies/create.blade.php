@extends('backend.master')

@section('title', 'Add Pharmacy')

@push('styles-top')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .service-pill-wrapper {
            position: relative;
            display: inline-block;
        }
        .service-pill-checkbox {
            display: none;
        }
        .service-pill-label {
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
        .service-pill-label:hover {
            background-color: #e5e7eb;
        }
        .service-pill-checkbox:checked + .service-pill-label {
            background-color: #f59e0b;
            color: #ffffff !important;
            border-color: #d97706;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
        }
        .service-pill-checkbox:checked + .service-pill-label::before {
            content: "✓ ";
            font-weight: bold;
            color: #ffffff !important;
        }
        .remove-service-pill {
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
        .remove-service-pill:hover {
            color: #ffffff !important;
            background-color: #ef4444;
        }
        #pharmacyMapPreview {
            height: 260px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Add Nearby Pharmacy</h5>
                        <p class="text-muted mb-0 fs-12">Add pharmacy details, address, phone number, auto-geocoded coordinates, services, and photo</p>
                    </div>
                    <a href="{{ route('backend.pharmacies.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('backend.pharmacies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Pharmacy Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Pharmacie Centrale" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                    <input type="text" name="phone" class="form-control" placeholder="e.g. +212 522 123 456" value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Rating (1.0 to 5.0)</label>
                                <input type="number" step="0.1" min="1" max="5" name="rating" class="form-control" value="{{ old('rating', '4.9') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Opening Hours</label>
                                <input type="text" name="opening_hours" class="form-control" placeholder="e.g. 08:00 - 22:00" value="{{ old('opening_hours', '08:00 - 22:00') }}">
                            </div>

                            <!-- Address Input with Auto-Geocoding -->
                            <div class="col-md-12 mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label fw-semibold mb-0">Pharmacy Address <span class="text-danger">*</span></label>
                                    <small class="text-primary"><i class="ri-magic-line me-1"></i> Auto-Geocodes coordinates as you type</small>
                                </div>
                                <input type="text" name="address" id="pharmacyAddressInput" class="form-control @error('address') is-invalid @enderror" placeholder="e.g. 5 Rue Allal Ben Abdellah, Casablanca" value="{{ old('address') }}" required>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Interactive Map Preview & Coordinates -->
                            <div class="col-md-12 mb-4">
                                <div class="card border bg-light p-3">
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark mb-0"><i class="ri-map-pin-2-fill text-danger me-1"></i> Map Location & Coordinates</label>
                                            <small class="text-muted d-block">Click on map or drag pin to fine-tune location</small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="button" class="btn btn-xs btn-outline-secondary" id="detectMyLocationBtn">
                                                <i class="ri-navigation-line me-1"></i> Detect My Current Location
                                            </button>
                                        </div>
                                    </div>

                                    <div id="pharmacyMapPreview" class="mb-3"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fs-12 fw-medium text-muted">Latitude</label>
                                            <input type="text" name="latitude" id="latInput" class="form-control form-control-sm bg-white" placeholder="e.g. 33.5731104" value="{{ old('latitude', '33.5731104') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-12 fw-medium text-muted">Longitude</label>
                                            <input type="text" name="longitude" id="lngInput" class="form-control form-control-sm bg-white" placeholder="e.g. -7.5898434" value="{{ old('longitude', '-7.5898434') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Services / Services Tags -->
                            <div class="col-md-12 mb-4">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <div>
                                        <label class="form-label fw-semibold mb-0">Pharmacy Services & Offers (Tags)</label>
                                        <small class="text-muted d-block fs-12">Select services provided by this pharmacy or type a new tag below</small>
                                    </div>
                                    
                                    <!-- Inline Add Tag Input Group -->
                                    <div class="input-group input-group-sm" style="max-width: 360px;">
                                        <input type="text" id="inlineServiceInput" class="form-control" placeholder="Type new tag (e.g. Vaccines, Delivery)">
                                        <button type="button" class="btn btn-warning fw-medium" id="inlineAddServiceBtn">
                                            <i class="ri-add-line me-1"></i> Add Tag
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded border" id="servicesContainer">
                                    @foreach($defaultServices as $sIdx => $service)
                                        <div class="service-pill-wrapper me-1 mb-1">
                                            <input type="checkbox" name="services[]" value="{{ $service }}" id="service_{{ $sIdx }}" class="service-pill-checkbox" {{ $sIdx < 3 ? 'checked' : '' }}>
                                            <label for="service_{{ $sIdx }}" class="service-pill-label">{{ $service }}</label>
                                            <span class="remove-service-pill" title="Remove Tag" onclick="removeServicePill(this)">&times;</span>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted mt-1 d-block">Click any pill to select/unselect. Click <strong>&times;</strong> to remove tag.</small>
                            </div>

                            <!-- Photo / Image Upload (Dropify) -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">Pharmacy Photo / Logo</label>
                                <input type="file" name="image" class="dropify" data-height="160" data-max-file-size="20M" accept="image/*" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                                    <label class="form-check-label fw-semibold" for="is_active">Active Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="ri-save-line me-1"></i> Save Pharmacy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function removeServicePill(btn) {
            $(btn).closest('.service-pill-wrapper').fadeOut(200, function() {
                $(this).remove();
            });
        }

        $(document).ready(function() {
            // Dropify Init
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop pharmacy photo here or click to browse',
                    'replace': 'Drag and drop or click to replace photo',
                    'remove':  'Remove',
                    'error':   'Error uploading image.'
                }
            });

            // Leaflet Map Picker Setup (Default Casablanca coordinates: 33.5731104, -7.5898434)
            var initialLat = parseFloat($('#latInput').val()) || 33.5731104;
            var initialLng = parseFloat($('#lngInput').val()) || -7.5898434;

            var map = L.map('pharmacyMapPreview').setView([initialLat, initialLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

            function updateInputs(lat, lng) {
                $('#latInput').val(lat.toFixed(7));
                $('#lngInput').val(lng.toFixed(7));
            }

            marker.on('dragend', function(e) {
                var pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // Address Input Auto-Geocoding with debounce
            var geocodeTimer;
            $('#pharmacyAddressInput').on('keyup input', function() {
                clearTimeout(geocodeTimer);
                var address = $(this).val().trim();
                if (address.length < 4) return;

                geocodeTimer = setTimeout(function() {
                    $.getJSON('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(address), function(data) {
                        if (data && data.length > 0) {
                            var lat = parseFloat(data[0].lat);
                            var lon = parseFloat(data[0].lon);
                            map.setView([lat, lon], 14);
                            marker.setLatLng([lat, lon]);
                            updateInputs(lat, lon);
                            toastr.info('Location coordinates auto-detected!');
                        }
                    });
                }, 800);
            });

            // Detect My Current Location Button
            $('#detectMyLocationBtn').on('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        var lat = pos.coords.latitude;
                        var lng = pos.coords.longitude;
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                        updateInputs(lat, lng);
                        toastr.success('Current location detected!');
                    }, function() {
                        toastr.error('Geolocation permission denied or unavailable.');
                    });
                }
            });

            // Add Custom Service Tag Helper
            function addServiceTag(tagText) {
                var tag = tagText ? tagText.trim() : '';
                if (!tag) return;

                var newId = 'srv_dyn_' + Date.now();
                var newPill = `<div class="service-pill-wrapper me-1 mb-1">
                    <input type="checkbox" name="services[]" value="${tag}" id="${newId}" class="service-pill-checkbox" checked>
                    <label for="${newId}" class="service-pill-label">${tag}</label>
                    <span class="remove-service-pill" title="Remove Tag" onclick="removeServicePill(this)">&times;</span>
                </div>`;

                $('#servicesContainer').append(newPill);
                $('#inlineServiceInput').val('');
                toastr.success('Tag "' + tag + '" added!');
            }

            $('#inlineAddServiceBtn').on('click', function() {
                addServiceTag($('#inlineServiceInput').val());
            });

            $('#inlineServiceInput').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    addServiceTag($(this).val());
                }
            });
        });
    </script>
@endpush
