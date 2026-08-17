<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-transparent py-3">
                <h4 class="card-title fw-bold text-dark mb-0 fs-16">Daily Performance Breakdown</h4>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex flex-column gap-3">
                    @foreach($daily_performance as $day)
                    <div class="p-3 rounded d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 shadow-sm border border-white" style="background-color: #f1f5f9;">
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark fs-14">{{ $day['date'] }}</span>
                            <span class="text-muted fs-12">{{ $day['orders_count'] }} orders</span>
                        </div>
                        <div class="d-flex flex-wrap gap-3 gap-md-5 align-items-center justify-content-between w-100 w-md-auto text-start text-md-end mt-2 mt-md-0 pt-2 pt-md-0 border-top border-md-0 border-light" style="border-top: 1px solid rgba(0,0,0,0.05);">
                            <div style="min-width: 80px;">
                                <span class="d-block text-muted fs-11 text-uppercase fw-medium">Sales</span>
                                <span class="fw-bold text-success fs-14">{{ number_format($day['sales'], 0, '.', ' ') }} DH</span>
                            </div>
                            <div style="min-width: 80px;">
                                <span class="d-block text-muted fs-11 text-uppercase fw-medium">Expenses</span>
                                <span class="fw-bold text-danger fs-14">{{ number_format($day['expenses'], 0, '.', ' ') }} DH</span>
                            </div>
                            <div style="min-width: 80px;">
                                <span class="d-block text-muted fs-11 text-uppercase fw-medium">Profit</span>
                                <span class="fw-bold @if($day['profit'] < 0) text-danger @else text-success @endif fs-14">
                                    {{ number_format($day['profit'], 0, '.', ' ') }} DH
                                </span>
                                <span class="d-block text-muted fs-10 fw-medium">{{ $day['margin'] }}% margin</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
