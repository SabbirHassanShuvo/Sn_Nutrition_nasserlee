@extends('backend.master')
@section('title', 'Dashboard | Product form')

@push('styles-top')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>
        .nav-tabs-custom .nav-link {
            border: none;
            font-weight: 500;
            color: #6c757d;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        .nav-tabs-custom .nav-link.active {
            color: #405189;
            background-color: #f3f3f9;
            border-bottom: 2px solid #405189;
        }
        .card-header-tabs {
            margin-bottom: -1px;
            border-bottom: 1px solid #e9ebec;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between px-0 bg-transparent">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0 text-dark fw-bold">{{ @$product ? 'Edit' : 'Create' }} Product</h4>
                    <a href="{{ route('backend.product.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ @$product ? route('backend.product.update', @$product->id) : route('backend.product.store')}}" enctype="multipart/form-data">
        @csrf
        @if (@$product)
            @method('PATCH')
        @endif
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header border-0 p-0">
                        <ul class="nav nav-tabs-custom rounded-top card-header-tabs border-bottom" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#basic-info" role="tab">
                                    <i class="ri-information-line align-middle me-1"></i> Basic Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#images-tab" role="tab">
                                    <i class="ri-image-line align-middle me-1"></i> Images
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#features-tab" role="tab">
                                    <i class="ri-list-check align-middle me-1"></i> Features
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ingredients-tab" role="tab">
                                    <i class="ri-leaf-line align-middle me-1"></i> Ingredients
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#nutrition-tab" role="tab">
                                    <i class="ri-table-line align-middle me-1"></i> Nutrition
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#usage-tab" role="tab">
                                    <i class="ri-guide-line align-middle me-1"></i> Usage
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Basic Info Tab -->
                            <div class="tab-pane active" id="basic-info" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" for="name">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{old('name', @$product->name)}}" class="form-control form-control-lg bg-light border-0 shadow-none" placeholder="Enter product name" required>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-4">
                                        <label class="form-label fw-semibold">Old Price (MAD)</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light">MAD</span>
                                            <input type="number" step="0.01" id="old_price" name="old_price" value="{{old('old_price', @$product->old_price)}}" class="form-control bg-light border-0 shadow-none" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-4">
                                        <label class="form-label fw-semibold">Discount (%)</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light">%</span>
                                            <input type="number" step="0.01" id="discount_percent" name="discount_percent" value="{{old('discount_percent', @$product->discount_percent)}}" class="form-control bg-light border-0 shadow-none" placeholder="0" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-4">
                                        <label class="form-label fw-semibold">Selling Price (MAD) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light">MAD</span>
                                            <input type="number" step="0.01" id="price" name="price" value="{{old('price', @$product->price)}}" class="form-control bg-light border-0 shadow-none" placeholder="0.00" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label fw-semibold">Brand</label>
                                        <select name="brand_id" id="brand_id" class="form-control" data-choices data-choices-groups data-placeholder="Select Brand">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', @$product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control" data-choices data-choices-groups data-placeholder="Select Category" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', @$product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label fw-semibold">Form (e.g. Capsule)</label>
                                        <input type="text" name="form" value="{{old('form', @$product->form)}}" class="form-control bg-light border-0 shadow-none" placeholder="e.g. Capsule, Powder">
                                    </div>
                                     <div class="col-lg-6 mb-4">
                                         <label class="form-label fw-semibold">Servings</label>
                                         <input type="number" name="servings" value="{{old('servings', @$product->servings)}}" class="form-control bg-light border-0 shadow-none" placeholder="Number of servings">
                                     </div>
                                     <div class="col-lg-6 mb-4">
                                         <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                                         <input type="number" name="quantity" value="{{old('quantity', @$product->quantity ?? 0)}}" class="form-control bg-light border-0 shadow-none" placeholder="Total quantity in stock" required>
                                     </div>
                                 </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Short Description</label>
                                    <textarea name="short_description" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="Enter a brief summary">{{old('short_description', @$product->short_description)}}</textarea>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label fw-semibold">Full Description</label>
                                    <textarea name="full_description" id="ckeditor-classic" class="form-control">{{old('full_description', @$product->full_description)}}</textarea>
                                </div>
                            </div>

                            <!-- Images Tab -->
                            <div class="tab-pane" id="images-tab" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Main Image</label>
                                    <input type="file" name="main_image" class="dropify" data-default-file="{{ @$product->main_image ? asset($product->main_image) : '' }}" data-height="250" accept="image/*" />
                                    <p class="text-muted mt-2 fs-12 italic"><i class="ri-information-line me-1"></i> Recommended: 800x800 px. Max 2MB.</p>
                                </div>
                                <hr class="my-4 text-muted opacity-25">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Gallery Images (Multiple)</label>
                                    <input type="file" id="galleryImagesInput" name="gallery_images[]" class="form-control bg-light border-0" multiple accept="image/*" onchange="previewGalleryImages(this, 'galleryPreviewContainer')">
                                    <div id="galleryPreviewContainer" class="d-flex flex-wrap gap-3 mt-3 p-3 bg-light rounded-3" style="border: 2px dashed #adb5bd; min-height: 150px; align-items: center; justify-content: center;">
                                        @if(@$product && $product->gallery_images)
                                            @foreach($product->gallery_images as $img)
                                                <div class="position-relative">
                                                    <img src="{{ asset($img) }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;" class="shadow-sm border">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted py-4">
                                                <i class="ri-gallery-upload-line fs-32 d-block mb-2 text-secondary"></i>
                                                <span class="fw-medium">No gallery images selected</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Tabs (Features, Ingredients, etc.) -->
                            @php
                                $tabs = [
                                    'features' => ['title', 'description', 'Feature'],
                                    'ingredients' => ['title', 'description', 'Ingredient'],
                                    'nutrition' => ['name', 'amount', 'Nutrition Info'],
                                ];
                            @endphp

                            @foreach($tabs as $key => $fields)
                                <div class="tab-pane" id="{{$key}}-tab" role="tabpanel">
                                    <div id="{{$key}}-container">
                                        @if(@$product && $product->$key->count() > 0)
                                            @foreach($product->$key as $index => $item)
                                                <div class="row {{$key}}-row mb-3 align-items-end">
                                                    <div class="col-md-5">
                                                        <label class="form-label fs-12 text-muted mb-1">{{ ucfirst($fields[0]) }}</label>
                                                        <input type="text" name="{{$key}}[{{$index}}][{{$fields[0]}}]" value="{{$item->{$fields[0]} }}" class="form-control bg-light border-0 shadow-none" placeholder="{{ $fields[2] }} {{ ucfirst($fields[0]) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fs-12 text-muted mb-1">{{ ucfirst($fields[1]) }}</label>
                                                        <input type="text" name="{{$key}}[{{$index}}][{{$fields[1]}}]" value="{{$item->{$fields[1]} }}" class="form-control bg-light border-0 shadow-none" placeholder="{{ $fields[2] }} {{ ucfirst($fields[1]) }}">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-soft-danger btn-icon remove-row"><i class="ri-delete-bin-line fs-16"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row {{$key}}-row mb-3 align-items-end">
                                                <div class="col-md-5">
                                                    <label class="form-label fs-12 text-muted mb-1">{{ ucfirst($fields[0]) }}</label>
                                                    <input type="text" name="{{$key}}[0][{{$fields[0]}}]" class="form-control bg-light border-0 shadow-none" placeholder="{{ $fields[2] }} {{ ucfirst($fields[0]) }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fs-12 text-muted mb-1">{{ ucfirst($fields[1]) }}</label>
                                                    <input type="text" name="{{$key}}[0][{{$fields[1]}}]" class="form-control bg-light border-0 shadow-none" placeholder="{{ $fields[2] }} {{ ucfirst($fields[1]) }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-soft-danger btn-icon remove-row"><i class="ri-delete-bin-line fs-16"></i></button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-success btn-sm mt-2 fw-medium" onclick="addRow('{{$key}}', ['{{$fields[0]}}', '{{$fields[1]}}'], '{{$fields[2]}}')">
                                        <i class="ri-add-line align-middle me-1"></i> Add {{ $fields[2] }}
                                    </button>
                                </div>
                            @endforeach

                            <!-- Usage Tab -->
                            <div class="tab-pane" id="usage-tab" role="tabpanel">
                                <div id="usages-container">
                                    @if(@$product && $product->usages->count() > 0)
                                        @foreach($product->usages as $index => $usage)
                                            <div class="row usage-row mb-3 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label fs-12 text-muted mb-1">Type</label>
                                                    <select name="usages[{{$index}}][type]" class="form-select bg-light border-0 shadow-none">
                                                        <option value="dosage" {{$usage->type == 'dosage' ? 'selected' : ''}}>Dosage Instruction</option>
                                                        <option value="best_moments" {{$usage->type == 'best_moments' ? 'selected' : ''}}>Best Moments</option>
                                                        <option value="warnings" {{$usage->type == 'warnings' ? 'selected' : ''}}>Warning</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label fs-12 text-muted mb-1">Content</label>
                                                    <input type="text" name="usages[{{$index}}][content]" value="{{$usage->content}}" class="form-control bg-light border-0 shadow-none" placeholder="Instruction/Warning content">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-soft-danger btn-icon remove-row"><i class="ri-delete-bin-line fs-16"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row usage-row mb-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label fs-12 text-muted mb-1">Type</label>
                                                <select name="usages[0][type]" class="form-select bg-light border-0 shadow-none">
                                                    <option value="dosage">Dosage Instruction</option>
                                                    <option value="best_moments">Best Moments</option>
                                                    <option value="warnings">Warning</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fs-12 text-muted mb-1">Content</label>
                                                <input type="text" name="usages[0][content]" class="form-control bg-light border-0 shadow-none" placeholder="Instruction/Warning content">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-soft-danger btn-icon remove-row"><i class="ri-delete-bin-line fs-16"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-soft-success btn-sm mt-2 fw-medium" onclick="addUsageRow()">
                                    <i class="ri-add-line align-middle me-1"></i> Add Usage Info
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mb-4">
                    <a href="{{route('backend.product.index')}}" class="btn btn-soft-danger w-sm me-2 fw-medium shadow-none">Cancel</a>
                    <button type="submit" class="btn btn-primary w-sm shadow-sm fw-medium">{{@$product ? 'Update' : 'Create'}} Product</button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-soft-primary border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold text-primary">Status & Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch form-switch-lg mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_vegan" name="is_vegan" {{ old('is_vegan', @$product->is_vegan) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium ms-2" for="is_vegan">Is Vegan Product?</label>
                        </div>
                        <div class="form-check form-switch form-switch-lg mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="in_stock" name="in_stock" {{ old('in_stock', @$product ? $product->in_stock : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium ms-2" for="in_stock">Currently In Stock?</label>
                        </div>
                        <div class="form-check form-switch form-switch-lg mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_popular" name="is_popular" {{ old('is_popular', @$product->is_popular) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium ms-2" for="is_popular">Mark as Popular?</label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-lg mt-4">
                    <div class="card-header bg-soft-info border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold text-info">Publishing Tips</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 fs-13 text-muted lh-lg">
                            <li><i class="ri-checkbox-circle-line text-success me-1"></i> Use a clear, high-quality main image.</li>
                            <li><i class="ri-checkbox-circle-line text-success me-1"></i> Gallery images help conversions.</li>
                            <li><i class="ri-checkbox-circle-line text-success me-1"></i> Fill out nutritional info for trust.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts-bottom')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="{{asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Dropify
            $('.dropify').dropify();

            // CKEditor
            if (document.querySelector("#ckeditor-classic")) {
                ClassicEditor.create(document.querySelector("#ckeditor-classic")).catch(error => {
                    console.error(error);
                });
            }

            // Remove dynamic row
            $(document).on('click', '.remove-row', function() {
                if($(this).closest('.tab-pane').find('.row').length > 1) {
                    $(this).closest('.row').remove();
                } else {
                    toastr.warning("At least one row is required.");
                }
            });

            // Auto-calculate price
            $('#old_price, #discount_percent').on('input', function() {
                let oldPrice = parseFloat($('#old_price').val()) || 0;
                let discount = parseFloat($('#discount_percent').val()) || 0;
                
                if (oldPrice > 0 && discount >= 0) {
                    let sellingPrice = oldPrice - (oldPrice * discount / 100);
                    $('#price').val(sellingPrice.toFixed(2));
                }
            });
        });

        // Gallery Preview
        function previewGalleryImages(input, containerId) {
            const container = document.getElementById(containerId);
            if (input.files && input.files.length > 0) {
                container.innerHTML = '';
                container.style.justifyContent = 'flex-start';
                for(let i=0; i<input.files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'position-relative';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'shadow-sm border';
                        img.style.width = '120px';
                        img.style.height = '120px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '8px';
                        div.appendChild(img);
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        }

        let indexes = {
            features: {{ @$product ? $product->features->count() : 1 }},
            ingredients: {{ @$product ? $product->ingredients->count() : 1 }},
            nutrition: {{ @$product ? $product->nutrition->count() : 1 }},
            usages: {{ @$product ? $product->usages->count() : 1 }}
        };

        function addRow(section, fields, label) {
            let idx = indexes[section]++;
            let html = `<div class="row ${section}-row mb-3 align-items-end anim-fade-in">
                <div class="col-md-5">
                    <label class="form-label fs-12 text-muted mb-1">${fields[0].charAt(0).toUpperCase() + fields[0].slice(1)}</label>
                    <input type="text" name="${section}[${idx}][${fields[0]}]" class="form-control bg-light border-0 shadow-none" placeholder="${label} ${fields[0]}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-12 text-muted mb-1">${fields[1].charAt(0).toUpperCase() + fields[1].slice(1)}</label>
                    <input type="text" name="${section}[${idx}][${fields[1]}]" class="form-control bg-light border-0 shadow-none" placeholder="${label} ${fields[1]}">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-soft-danger btn-icon remove-row"><i class="ri-delete-bin-line fs-16"></i></button>
                </div>
            </div>`;
            $('#' + section + '-container').append(html);
        }

        function addUsageRow() {
            let idx = indexes.usages++;
            let html = `<div class="row usage-row mb-3 align-items-end anim-fade-in">
                <div class="col-md-3">
                    <label class="form-label fs-12 text-muted mb-1">Type</label>
                    <select name="usages[${idx}][type]" class="form-select bg-light border-0 shadow-none">
                        <option value="dosage">Dosage Instruction</option>
                        <option value="best_moments">Best Moments</option>
                        <option value="warnings">Warning</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fs-12 text-muted mb-1">Content</label>
                    <input type="text" name="usages[${idx}][content]" class="form-control bg-light border-0 shadow-none" placeholder="Instruction/Warning content">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-soft-danger btn-icon remove-row"><i class="ri-delete-bin-line fs-16"></i></button>
                </div>
            </div>`;
            $('#usages-container').append(html);
        }
    </script>
@endpush
