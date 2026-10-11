{{-- Single packing sticker markup. Expects $order. CSS lives in the parent shell. --}}
<div class="slip">
    <table class="head">
        <tr>
            <td style="width: 34px;"><div class="brand-icon">S</div></td>
            <td>
                <h1>ShopLink</h1>
                <small>PACKING STICKER</small>
            </td>
        </tr>
    </table>
    <div class="slip-body">
        <div class="order-no">{{ $order->order_number }}</div>
        <div class="meta">Placed {{ $order->created_at ? $order->created_at->format('F d, Y h:i A') : '—' }}</div>
        <span class="status-pill">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
        <table class="info">
            <tr>
                <td>
                    <h4>SHIP / DELIVER TO</h4>
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->customer_phone ?? '' }}<br>
                    @if($order->delivery_method === 'delivery' && $order->delivery_address)
                        {{ $order->delivery_address }}
                    @else
                        Store pickup
                    @endif
                </td>
                <td>
                    <h4>ORDER INFO</h4>
                    {{ $order->delivery_method === 'delivery' ? 'Delivery' : 'Pickup' }}<br>
                    {{ ucfirst($order->payment_method ?? '') }} ({{ ucfirst($order->payment_status ?? '') }})<br>
                    {{ $order->items->sum('quantity') }} pcs
                </td>
            </tr>
        </table>
        <table class="items">
            <tr>
                <th>Item</th>
                <th class="c">Qty</th>
                <th class="r">Amount</th>
            </tr>
            @foreach($order->items->take(4) as $item)
                <tr>
                    <td>{{ $item->product_name }}@if($item->variation_name)<br><span style="color:#71717a;">{{ $item->variation_name }}</span>@endif</td>
                    <td class="c">{{ $item->quantity }}</td>
                    <td class="r">₱{{ number_format(($item->subtotal ?? $item->price * $item->quantity), 2) }}</td>
                </tr>
            @endforeach
            @if($order->items->count() > 4)
                <tr><td colspan="3">+{{ $order->items->count() - 4 }} more item(s) — see full slip</td></tr>
            @endif
        </table>
        <table class="totals">
            <tr><td>Subtotal</td><td class="r">₱{{ number_format($order->subtotal, 2) }}</td></tr>
            <tr><td>Tax (12%)</td><td class="r">₱{{ number_format($order->tax, 2) }}</td></tr>
            <tr><td>Delivery Fee</td><td class="r">@if($order->delivery_fee > 0)₱{{ number_format($order->delivery_fee, 2) }}@else Free @endif</td></tr>
            <tr><td>Discount</td><td class="r">-₱{{ number_format($order->discount, 2) }}</td></tr>
            <tr class="grand"><td>Total</td><td class="r">₱{{ number_format($order->total, 2) }}</td></tr>
        </table>
        @if($order->notes)
            <div class="notes"><strong>Note:</strong> {{ $order->notes }}</div>
        @endif
        <table class="pack">
            <tr>
                <td>Packed by: _______</td>
                <td style="text-align:right;">Checked by: _______</td>
            </tr>
        </table>
        <div class="thanks">Thank you for shopping with ShopLink!</div>
    </div>
</div>
