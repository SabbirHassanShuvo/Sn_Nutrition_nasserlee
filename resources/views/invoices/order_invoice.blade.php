<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} - SN Nutrition</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 40px 20px;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #10b981;
            padding-bottom: 24px;
            margin-bottom: 32px;
        }
        .brand-logo {
            font-size: 28px;
            font-weight: 800;
            color: #10b981;
            letter-spacing: -0.5px;
        }
        .brand-logo span {
            color: #047857;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-size: 24px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-title p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 36px;
        }
        .info-block h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .info-block p {
            font-size: 14px;
            color: #334155;
            line-height: 1.6;
        }
        .info-block strong {
            color: #0f172a;
        }
        .table-container {
            margin-bottom: 32px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            text-align: left;
        }
        td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
        }
        td.amount, th.amount {
            text-align: right;
        }
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 36px;
        }
        .summary-box {
            width: 300px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #64748b;
        }
        .summary-row.total {
            border-top: 2px solid #e2e8f0;
            padding-top: 12px;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .summary-row.total span.amount {
            color: #10b981;
        }
        .footer-note {
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 24px;
            color: #94a3b8;
            font-size: 13px;
        }
        .print-btn {
            display: inline-block;
            background: #10b981;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .invoice-card {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div style="text-align: right; max-width: 800px; margin: 0 auto;">
        <button onclick="window.print()" class="print-btn">🖨️ Print / Save as PDF</button>
    </div>

    <div class="invoice-card">
        <div class="invoice-header">
            <div class="brand-logo">
                SN <span>Nutrition</span>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>#INV-{{ str_replace('SN-', '', $order->order_number) }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h3>Billed To</h3>
                <p><strong>{{ $order->full_name ?: (optional($order->user)->name ?: 'Customer') }}</strong></p>
                <p>{{ $order->address }}</p>
                <p>{{ $order->city ? $order->city . ', ' : '' }}{{ $order->country ?: 'Morocco' }}</p>
                <p>{{ $order->phone }}</p>
                <p>{{ $order->email }}</p>
            </div>
            <div class="info-block">
                <h3>Order Info</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at ? $order->created_at->format('M d, Y') : '' }}</p>
                <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?: 'COD') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th class="amount">Unit Price</th>
                        <th class="amount">Qty</th>
                        <th class="amount">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ optional($item->product)->name ?: 'Product' }}</strong>
                            </td>
                            <td class="amount">{{ number_format($item->price, 2) }} MAD</td>
                            <td class="amount">{{ $item->quantity }}</td>
                            <td class="amount">{{ number_format($item->price * $item->quantity, 2) }} MAD</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="amount">{{ number_format($order->subtotal, 2) }} MAD</span>
                </div>
                <div class="summary-row">
                    <span>Delivery Fee</span>
                    <span class="amount">{{ number_format($order->delivery_fee, 2) }} MAD</span>
                </div>
                @if($order->discount > 0)
                <div class="summary-row">
                    <span>Discount</span>
                    <span class="amount">-{{ number_format($order->discount, 2) }} MAD</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Total</span>
                    <span class="amount">{{ number_format($order->total, 2) }} MAD</span>
                </div>
            </div>
        </div>

        <div class="footer-note">
            <p>Thank you for shopping with SN Nutrition!</p>
            <p style="margin-top: 4px;">If you have any questions about this invoice, contact contact@snnutrition.com</p>
        </div>
    </div>

</body>
</html>
