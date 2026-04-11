<div class="table-responsive">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Date</th>
                <th class="text-end">Order Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $i => $order)
                <tr>
                    <td>{{ method_exists($orders, 'firstItem') ? $orders->firstItem() + $i : $loop->iteration }}</td>
                    <td>{{ $order->{$fieldMap['order_number']} ?? '-' }}</td>
                    <td>{{ optional($order->user)->name ?: 'Guest User' }}</td>
                    <td>{{ (is_array($order->billing_address) ? ($order->billing_address['phone_number'] ?? '-') : '-')  }}</td>
                    @php
                        $dateValue = $order->{$fieldMap['date']} ?? null;
                        if ($dateValue instanceof \Carbon\CarbonInterface) {
                            $dateStr = $dateValue->format('Y-m-d H:i');
                        } elseif (is_object($dateValue) && method_exists($dateValue, 'format')) {
                            $dateStr = $dateValue->format('Y-m-d H:i');
                        } else {
                            $dateStr = is_string($dateValue) ? $dateValue : '-';
                        }
                        $orderAmount = $order->{$fieldMap['order_amount']} ?? 0;
                        $deliveryAmount = $order->{$fieldMap['delivery_amount']} ?? 0;
                    @endphp
                    <td>{{ $dateStr }}</td>
                    <td class="text-end">
                        {{ number_format((float) $orderAmount, 3) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No orders found for the selected range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>