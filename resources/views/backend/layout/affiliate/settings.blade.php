@extends('backend.master')

@section('title', 'Affiliate Tier Settings')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Affiliate Partner Tier Settings</h5>
                        <p class="text-muted mb-0 fs-12">Manage monthly threshold limits ($) and commission bonus percentages (%) for each tier level.</p>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('t-success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <strong>Success!</strong> {{ session('t-success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('backend.affiliate-setting.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;">Tier Level</th>
                                        <th>Monthly Min Earnings Threshold ($)</th>
                                        <th>Commission Bonus (%)</th>
                                        <th style="width: 120px;">Preview Badge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tiers as $index => $tier)
                                        <tr>
                                            <input type="hidden" name="tiers[{{ $index }}][id]" value="{{ $tier->id }}">
                                            <td>
                                                <span class="fw-bold fs-14 text-dark">{{ $tier->tier_name }}</span>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" min="0" 
                                                           name="tiers[{{ $index }}][min_earnings]" 
                                                           value="{{ $tier->min_earnings }}" 
                                                           class="form-control" required>
                                                </div>
                                                <small class="text-muted">Min monthly sales required</small>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0" max="100" 
                                                           name="tiers[{{ $index }}][bonus_percent]" 
                                                           value="{{ $tier->bonus_percent }}" 
                                                           class="form-control" required>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                <small class="text-muted">Added bonus on top of base product %</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge px-3 py-2 fs-12 text-uppercase" 
                                                      style="background-color: {{ $tier->badge_color ?: '#405189' }}; color: #fff;">
                                                    +{{ $tier->bonus_percent }}% Bonus
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="ri-save-line me-1 align-bottom"></i> Save Tier Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
