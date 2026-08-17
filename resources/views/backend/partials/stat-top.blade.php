<!-- Analytics Period Filter Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-3">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="text-muted fw-medium fs-13">Analytics Period:</span>
                    <div class="d-flex align-items-center gap-2 w-100 w-sm-auto mt-1 mt-sm-0">
                        <input type="date" class="form-control form-control-sm border shadow-sm px-2 py-1 bg-light text-dark rounded" style="width: 130px;" value="2026-08-01">
                        <span class="text-muted">-</span>
                        <input type="date" class="form-control form-control-sm border shadow-sm px-2 py-1 bg-light text-dark rounded" style="width: 130px;" value="2026-08-17">
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-1 gap-sm-2">
                    <button class="btn btn-xs btn-sm btn-outline-success bg-white font-medium fs-12 px-2 px-sm-3 py-1 rounded" style="border-color: #10b981; color: #10b981;">Today</button>
                    <button class="btn btn-xs btn-sm btn-outline-success bg-white font-medium fs-12 px-2 px-sm-3 py-1 rounded" style="border-color: #10b981; color: #10b981;">7 Days</button>
                    <button class="btn btn-xs btn-sm btn-outline-success bg-white font-medium fs-12 px-2 px-sm-3 py-1 rounded" style="border-color: #10b981; color: #10b981;">This Month</button>
                    <button class="btn btn-xs btn-sm btn-outline-success bg-white font-medium fs-12 px-2 px-sm-3 py-1 rounded" style="border-color: #10b981; color: #10b981;">Past Month</button>
                    <button class="btn btn-xs btn-sm btn-outline-success bg-white font-medium fs-12 px-2 px-sm-3 py-1 rounded" style="border-color: #10b981; color: #10b981;">This Year</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Overview Cards -->
<div class="row">
    <!-- Total Sales Card -->
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-animate border-0 shadow-sm mb-0 h-100" style="border-radius: 8px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-medium text-uppercase fs-12 mb-2">Total Sales</p>
                        <h3 class="fw-bold fs-22 mb-1" style="color: #1e293b;">
                            {{ number_format($total_sales, 0, '.', ' ') }} DH
                        </h3>
                        <p class="text-success fw-semibold fs-12 mb-0">
                            <i class="ri-arrow-right-up-line align-middle"></i> {{ $total_orders }} orders
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle fs-4" style="background-color: #22c55e; color: #ffffff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-money-dollar-circle-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Expenses Card -->
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-animate border-0 shadow-sm mb-0 h-100" style="border-radius: 8px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-medium text-uppercase fs-12 mb-2">Total Expenses</p>
                        <h3 class="fw-bold fs-22 mb-1" style="color: #1e293b;">
                            {{ number_format($total_expenses, 0, '.', ' ') }} DH
                        </h3>
                        <p class="text-muted fs-12 mb-0">
                            <i class="ri-time-line align-middle"></i> Various categories
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle fs-4" style="background-color: #0d9488; color: #ffffff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-file-list-3-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Profit Card -->
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-animate border-0 shadow-sm mb-0 h-100" style="border-radius: 8px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-medium text-uppercase fs-12 mb-2">Net Profit</p>
                        <h3 class="fw-bold fs-22 mb-1 @if($net_profit < 0) text-danger @else text-success @endif">
                            @if($net_profit < 0)-@endif{{ number_format(abs($net_profit), 0, '.', ' ') }} DH
                        </h3>
                        <p class="@if($profit_margin < 0) text-danger @else text-success @endif fw-semibold fs-12 mb-0">
                            <i class="ri-line-chart-line align-middle"></i> {{ $profit_margin }}% margin
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle fs-4" style="background-color: #10b981; color: #ffffff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-presentation-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Card -->
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card card-animate border-0 shadow-sm mb-0 h-100" style="border-radius: 8px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-medium text-uppercase fs-12 mb-2">Low Stock Alert</p>
                        <h3 class="fw-bold fs-22 mb-1" style="color: #1e293b;">
                            {{ $low_stock_count }} items
                        </h3>
                        <p class="text-danger fw-semibold fs-12 mb-0">
                            <i class="ri-alert-line align-middle"></i> Need reorder
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title rounded-circle fs-4" style="background-color: #ef4444; color: #ffffff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-error-warning-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>