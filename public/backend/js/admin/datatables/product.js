(function ($) {
    "use strict";
    $(document).ready(function () {
        $('#ProductTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            columns: [
                {
                    data: 'select',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'PrimaryImage',
                    name: 'PrimaryImage'
                },
                {
                    data: 'ProductName',
                    name: 'ProductName',
                },
                {
                    data: 'Barcode',
                    name: 'Barcode',
                },
                {
                    data: 'Category',
                    name: 'Category'
                },
                {
                    data: 'subcategory',
                    name: 'subcategory'
                },
                {
                    data: 'Price',
                    name: 'Price'
                },
                {
                    data: 'Type',
                    name: 'Type'
                },
                {
                    data: 'Status',
                    name: 'Status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

        // Bulk Selection Logic
        $('#selectAll').on('click', function () {
            $('.product-select').prop('checked', this.checked);
            toggleBulkButton();
        });

        $(document).on('click', '.product-select', function () {
            if ($('.product-select:checked').length == $('.product-select').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
            toggleBulkButton();
        });

        function toggleBulkButton() {
            if ($('.product-select:checked').length > 0) {
                $('#bulkActiveBtn').removeClass('d-none');
            } else {
                $('#bulkActiveBtn').addClass('d-none');
            }
        }

        $('#bulkActiveBtn').on('click', function () {
            let ids = [];
            $('.product-select:checked').each(function () {
                ids.push($(this).val());
            });

            if (ids.length > 0) {
                if (confirm('Are you sure you want to activate selected products?')) {
                    $.ajax({
                        url: "/admin/product/bulk-active",
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            ids: ids
                        },
                        success: function (res) {
                            if (res.success) {
                                toastr.success(res.message);
                                $('#ProductTable').DataTable().ajax.reload();
                                $('#bulkActiveBtn').addClass('d-none');
                                $('#selectAll').prop('checked', false);
                            } else {
                                toastr.error(res.message);
                            }
                        }
                    });
                }
            }
        });

        $(document).on('click', '.btn-stock-check', function () {
            let id = $(this).data('id');
            let url = "/admin/product/stock-breakdown/" + id;

            $('#stockBreakdownModal').modal('show');
            $('#sb-product-name').text('Loading...');
            $('#sb-virtual-stock').text('...');
            $('#sb-components-table').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {
                    $('#sb-product-name').text(res.product_name);
                    $('#sb-virtual-stock').text(res.virtual_stock);

                    let rows = '';
                    res.components.forEach(function (c) {
                        let stockClass = c.current_stock <= 0 ? 'text-danger font-weight-bold' : (c.current_stock < c.required_per_combo * 10 ? 'text-warning' : 'text-success');
                        let possibleBadge = c.max_combinations >= 50 ? 'badge-success' : (c.max_combinations >= 10 ? 'badge-warning' : 'badge-danger');

                        rows += `<tr>
                            <td class="font-weight-bold">${c.name}</td>
                            <td class="text-center"><span class="badge badge-primary">${c.required_per_combo}</span></td>
                            <td class="text-center ${stockClass}">${c.current_stock}</td>
                            <td class="text-center"><span class="badge ${possibleBadge} px-3 py-2" style="font-size: 0.95rem;">${c.max_combinations}</span></td>
                        </tr>`;
                    });
                    $('#sb-components-table').html(rows);
                },
                error: function (err) {
                    $('#sb-product-name').text('Error');
                    $('#sb-components-table').html('<tr><td colspan="4" class="text-center text-danger">Failed to load data</td></tr>');
                    console.error(err);
                }
            });
        });
    });
})(jQuery)
