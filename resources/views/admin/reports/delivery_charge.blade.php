<!DOCTYPE html>
<html lang="en">
@extends('admin.master')
@section('title', __('Delivery Charge Report'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-truck mr-2"></i> {{ __('Monthly Delivery Charge Report') }}</h4>
                </div>
                <div class="card-body">
                    <!-- <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="daterange" class="form-control datepicker" placeholder="{{ __('Filter by date range') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="filterBtn" type="button"><i class="fas fa-filter"></i> {{ __('Filter') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <form action="{{ route('admin.export.delivery.charge.report') }}" method="POST" id="exportForm">
                                    @csrf
                                    <input type="hidden" name="start_date" id="start_date">
                                    <input type="hidden" name="end_date" id="end_date">
                                    <button type="button" class="btn btn-success" id="exportBtn">
                                        <i class="fas fa-file-excel mr-1"></i> {{ __('Export to Excel') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="reportTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th><i class="fas fa-calendar-alt mr-1"></i> {{ __('Year') }}</th>
                                    <th><i class="fas fa-calendar-alt mr-1"></i> {{ __('Month') }}</th>
                                    <th><i class="fas fa-shopping-cart mr-1"></i> {{ __('Total Orders') }}</th>
                                    <th><i class="fas fa-money-bill-wave mr-1"></i> {{ __('Total Delivery Charge') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $report->year }}</td>
                                        <td>{{ date('F', mktime(0, 0, 0, $report->month, 1)) }}</td>
                                        <td>
                                            <span class="badge badge-pill badge-info">{{ $report->total_orders }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-success">{{ number_format($report->total_delivery_charge, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="2" class="text-right">{{ __('Grand Total:') }}</td>
                                    <td>{{ $reports->sum('total_orders') }}</td>
                                    <td>{{ number_format($reports->sum('total_delivery_charge'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize datepicker if available
            if($.fn.daterangepicker) {
                $('input[name="daterange"]').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });

                $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
                });

                $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#start_date').val('');
                    $('#end_date').val('');
                });
            }

            // Export Excel functionality
            $('#exportBtn').click(function() {
                // Submit the form to export route
                $('#exportForm').submit();
            });

            // Filter functionality (you can implement this if needed)
            $('#filterBtn').click(function() {
                var dateRange = $('input[name="daterange"]').val();
                if (dateRange) {
                    // Implement filtering logic here if needed
                    alert('Filter will be applied with date range: ' + dateRange);
                } else {
                    alert('Please select a date range first');
                }
            });
        });
    </script>
    @endpush
@endsection
</html>