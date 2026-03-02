(function ($) {
    "use strict";

    // Helper to format currency
    function formatCurrency(amount) {
        // You might want to fetch the symbol or use a global variable if available
        // For now, assuming OMR or generic
        return amount.toFixed(3) + " OMR";
    }

    // UPDATE QUANTITY
    function updateCartQuantity(rowId, action, currentQty) {
        let url = action === 'increase'
            ? $('#CartIncrementFromSession').data('url')
            : $('#CartDecrementFromSession').data('url');

        $.ajax({
            method: "GET",
            url: url,
            data: {
                id: rowId,
                quantity: currentQty
            },
            success: function (data) {
                // data[0] = count
                // data[1] = total amount (float)
                // data[2] = cart content object
                // data['total_amount_formatted'] = formatted string

                // Update formatted totals
                $('.totalCountItem').text(data[0]);
                $('.totalAmount').text(data['total_amount_formatted']);
                $('.cart-page-final-total').text(data['total_amount_formatted']); // Update Grand Total

                // Update specific row details
                let cartItems = data[2];
                if (cartItems && cartItems[rowId]) {
                    let item = cartItems[rowId];
                    // Update input value
                    $(`.qty_value[data-id="${rowId}"]`).val(item.qty); // If input has data-id
                    // Or find input relative to button if logic differs

                    // Update Item Subtotal
                    if (data['subtotal_formatted']) {
                        row.find('.SubTotalAmount').text(data['subtotal_formatted']);
                    } else {
                        // Fallback
                        let itemSubtotal = item.qty * item.price;
                        row.find('.SubTotalAmount').text(itemSubtotal.toFixed(3) + " OMR");
                    }
                }

                // Toast Notification
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Cart Updated'
                });
            },
            error: function (xhr) {
                console.error("Cart update failed", xhr);
            }
        });
    }

    // CLICK HANDLERS
    $(document).on('click', '.qty_increase', function (e) {
        e.preventDefault();
        let rowId = $(this).data('id');
        let input = $(this).siblings('.qty_value');
        let currentQty = parseInt(input.val());
        updateCartQuantity(rowId, 'increase', currentQty);
    });

    $(document).on('click', '.qty_decrease', function (e) {
        e.preventDefault();
        let rowId = $(this).data('id');
        let input = $(this).siblings('.qty_value');
        let currentQty = parseInt(input.val());

        if (currentQty > 1) {
            updateCartQuantity(rowId, 'decrease', currentQty);
        }
    });

    // Handle Delete
    $(document).on('click', '.deleteItemCart', function (e) {
        e.preventDefault();
        let rowId = $(this).data('id');
        let url = $('#CartDeleteFromSession').data('url');
        let $this = $(this);

        $.ajax({
            method: "GET",
            url: url,
            data: { id: rowId },
            success: function (data) {
                $this.closest('.card').remove(); // Remove row
                $('.totalCountItem').text(data[0]);
                $('.totalAmount').text(data['total_amount_formatted']);
                $('.cart-page-final-total').text(data['total_amount_formatted']);

                // If empty
                if (data[0] == 0) {
                    location.reload();
                }
            }
        });
    });

})(jQuery);
