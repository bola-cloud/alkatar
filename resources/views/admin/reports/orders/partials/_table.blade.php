@php
    // All rendering uses the detected fieldMap. If any field appears null in your dataset, adjust mapping in controller.
    $dateCol = $fieldMap['date'] ?? 'created_at';
    $numCol = $fieldMap['number'] ?? 'Order_Number';
    $totCol = $fieldMap['total'] ?? 'Grand_Total';
    $delCol = $fieldMap['delivery'] ?? 'Delivery_Charge';
    $statusCol = $fieldMap['status'] ?? null;
@endphp

<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Date</th>
                <th class="text-end">Order Amount</th>
                <th class="text-end">Delivery Amount</th>
                @if($statusCol)
                    <th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->{$numCol} ?? '-' }}</td>
                    <td>{{ optional($order->user)->name ?? 'Guest' }}</td>
                    <td>{{ optional($order->{$dateCol})->format('Y-m-d H:i') ?? \Illuminate\Support\Carbon::parse($order->{$dateCol})->format('Y-m-d H:i') }}</td>
                    <td class="text-end">{{ number_format((float)($order->{$totCol} ?? 0), 2) }}</td>
                    <td class="text-end">{{ number_format((float)($order->{$delCol} ?? 0), 2) }}</td>
                    @if($statusCol)
                        <td>
                            @php $statusVal = $order->{$statusCol}; @endphp
                            {{ is_numeric($statusVal) ? ($order->getStatusLang()[$statusVal]['status_en'] ?? $statusVal) : $statusVal }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
