(function ($) {
    "use strict";
    $(document).ready(function () {
        var table = $('#AdvertiseTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return '<input type="checkbox" name="order_ids[]" value="' + data + '">';
                    }
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'Order_Number',
                    name: 'Order_Number'
                },
                {
                    data: 'User',
                    name: 'User'
                },
                {
                    data: 'Products',
                    name: 'Products'
                },
                {
                    data: 'GrandTotal',
                    name: 'GrandTotal'
                },
                {
                    data: 'Coupon',
                    name: 'Coupon'
                },
                {
                    data: 'Payment_Method',
                    name: 'Payment_Method'
                },
                {
                    data: 'digital_goods',
                    name: 'digital_goods'
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

        // Select all checkbox functionality
        $('#select-all-checkbox').on('change', function() {
            $('input[name="order_ids[]"]').prop('checked', this.checked);
        });

        // Deselect "select all" if any checkbox is unchecked
        $('#AdvertiseTable').on('change', 'input[name="order_ids[]"]', function() {
            if (!this.checked) {
                $('#select-all-checkbox').prop('checked', false);
            }
        });

        // Bulk action form submission
        $('#bulk-action-form').on('submit', function(e) {
            e.preventDefault();
            if ($('input[name="order_ids[]"]:checked').length > 0) {
                if (confirm("Are you sure you want to update the status of selected orders?")) {
                    this.submit();
                }
            } else {
                alert("Please select at least one order.");
            }
        });
    });

    $('#print-now').on('click', function () {
        let w = window.open();
        w.document.write(document.getElementById('printDiv').innerHTML);
        w.print();
        w.close();
    });
})(jQuery);

function orderDetails(id) {
    $.post(
        ROUTE_ORDER_DETAILS,
        {
            _token: CSRF_TOKEN,
            id: id,
        },
        function (data) {
            $(".modal-dialog").addClass('modal-lg');
            $(".modal-dialog").removeClass('modal-sm');
            $(".modal-content").html(data);
            $("#dataModal").modal("show");
        }
    );
}

function orderStatusEdit(id) {
    $.post(
        ROUTE_ORDER_STATUS_EDIT,
        {
            _token: CSRF_TOKEN,
            id: id,
        },
        function (data) {
            $(".modal-dialog").addClass('modal-sm');
            $(".modal-dialog").removeClass('modal-lg');
            $(".modal-content").html(data);
            $("#dataModal").modal("show");
        }
    );
}