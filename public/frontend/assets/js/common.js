(function ($) {
    "use strict";

    let sizeid;
    let isCartUpdating = false;
    var $loading = $("#loadingDiv").hide();
    $(".MyWishList").on("click", function () {
        let $btn = $(this);
        let product_id = $btn.attr("data-id");
        $.ajax({
            url: $("#productWishlistUrl").data("url"),
            method: "get",
            data: {
                product_id: product_id,
            },
            success: function (data) {
                // Update wishlist count if provided
                if (data.wishlist_count !== undefined) {
                    $(".wishListCuntFromController").html(
                        data.wishlist_count + " Items"
                    );
                }

                // Visual feedback: mark the heart as filled when added or already present
                if (data.status === 1 || data.message?.toLowerCase?.().includes('already')) {
                    $btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: "center",
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'premium-toast-glass'
                    }
                });

                if (data.status === 0) {
                    Toast.fire({ icon: "warning", title: data.message });
                } else if (data.status === 1) {
                    Toast.fire({ icon: "success", title: data.message });
                } else {
                    Toast.fire({ icon: "error", title: "Something went wrong!" });
                }
            },
            error: function () {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "center",
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'premium-toast-glass'
                    }
                });
                Toast.fire({ icon: "error", title: "Something went wrong!" });
            }
        });
    });

    $(".CompareList").on("click", function () {
        let product_id = $(this).attr("data-id");
        $.ajax({
            url: $("#AddToCompareItemUrl").data("url"),
            method: "get",
            data: {
                product_id: product_id,
            },
            success: function (data) {
                if (data.status === 0) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'premium-toast-glass'
                        }
                    });
                    Toast.fire({ icon: "warning", title: data.message });
                } else if (data.status === 1) {
                    $(".CompareCuntFromController").html(data.compare_count + " Items");
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'premium-toast-glass'
                        }
                    });
                    Toast.fire({ icon: "success", title: data.message });
                } else {
                    const Toast = Swal.mixin({
                        toast: false,
                        position: "center",
                        backdrop: false,
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'premium-toast-glass'
                        }
                    });
                    Toast.fire({ icon: "error", title: "Something went wrong!" });
                }
            },
        });
    });

    $(document).ready(function () {
        var isProductDetailsPage = $(".product-single-area").length > 0;

        function performAddToCart(productId, quantity, unitAmount, unitType, colorId, sizeId, weightId, additions, price) {
            return $.ajax({
                url: $("#AddToCartIntoSession").data("url"),
                method: "POST",
                data: {
                    product_id: productId,
                    quantity: quantity,
                    unit_amount: unitAmount,
                    unit_type: unitType,
                    color_id: colorId,
                    size_id: sizeId,
                    weight_id: weightId,
                    additions: additions,
                    price: price,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                }
            });
        }

        function showCartSuccess(data) {
            if (data.error) {
                Swal.fire({
                    icon: "error",
                    title: data.error,
                    confirmButtonText: "OK"
                });
                return;
            }

            $(".totalCountItem").html(data[0]);
            $(".totalAmount").html(window.currencyPrice(data[1]));
            window.recalcFinalTotal(data[1]);

            // Remove any existing cart notification
            var existing = document.getElementById('custom-cart-toast');
            if (existing) existing.remove();

            var title = (typeof localizedText !== 'undefined' && localizedText.productAddedToCart)
                ? localizedText.productAddedToCart
                : 'Product Added to Cart Successfully';

            // Build the notification element
            var toast = document.createElement('div');
            toast.id = 'custom-cart-toast';
            toast.innerHTML =
                '<span id="custom-cart-toast-close" style="position:absolute;top:8px;right:12px;font-size:1.3rem;cursor:pointer;color:#64748b;line-height:1;z-index:2;">&times;</span>' +
                '<span style="color:#16a34a;font-size:1.5rem;margin-right:10px;">&#10003;</span>' +
                '<span style="font-weight:600;font-size:1rem;color:#1e293b;flex:1;">' + title + '</span>' +
                '<div id="custom-cart-toast-bar" style="position:absolute;bottom:0;left:0;height:4px;width:100%;background:#a3c613;border-radius:0 0 16px 16px;transition:width 5s linear;"></div>';

            document.body.appendChild(toast);

            // Let the browser paint, then animate in and start bar countdown
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translate(-50%, -50%) scale(1)';
                    var bar = document.getElementById('custom-cart-toast-bar');
                    if (bar) bar.style.width = '0%';
                });
            });

            // Auto-close after 5s
            var autoClose = setTimeout(function () {
                closeToast(toast);
            }, 5000);

            // Close button
            var closeBtn = document.getElementById('custom-cart-toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    clearTimeout(autoClose);
                    closeToast(toast);
                });
            }

            function closeToast(el) {
                el.style.opacity = '0';
                el.style.transform = 'translate(-50%, -50%) scale(0.85)';
                setTimeout(function () {
                    if (el && el.parentNode) el.parentNode.removeChild(el);
                }, 350);
            }
        }

        window.performAddToCart = performAddToCart;
        window.showCartSuccess = showCartSuccess;

        let selectedProductId = null;
        let selectedSizeId = null;
        let selectedWeightId = null;
        let selectedSizePrice = 0;
        let selectedWeightPrice = 0;
        let discountPercenteng = 0;
        let selectedAdditions = [];
        // when options are populated with already-discounted prices (modal options),
        // we should not re-apply the percent discount at submit time.
        let optionsHaveDiscount = false;

        if (isProductDetailsPage) {
            // ... (keep detail page specific vars)
            discountPercenteng = parseFloat($('.addCart').first().data('percenteng')) || parseFloat($('.add-cart').first().data('percenteng')) || 0;
        }

        function isEmptyVariation(val) {
            if (val === undefined || val === null) return true;
            if (typeof val === 'string') {
                val = val.trim();
                if (val === '' || val === '[]' || val === '{}') return true;
                try {
                    let parsed = JSON.parse(val);
                    return isEmptyVariation(parsed);
                } catch (e) {
                    return false;
                }
            }
            if (Array.isArray(val)) {
                return val.length === 0 || val.every(item => item === null || item === undefined || (typeof item === 'object' && Object.keys(item).length === 0));
            }
            if (typeof val === 'object') return Object.keys(val).length === 0;
            return false;
        }

        // Use delegation for dynamic elements (like carousels)
        $(document).on("click", ".addCart", function () {
            // If on product detail page, standard addCart might need to behave differently or we use a separate class
            if (isProductDetailsPage && $(this).hasClass('addCartModal')) return;

            var productId = $(this).data("id");
            $("#submitSelection").hide();
            selectedProductId = productId;
            var productName = $(this).data("name");
            var sizes = $(this).data("sizes") || [];
            var weights = $(this).data("weights") || [];
            var additions = $(this).data("additions") || [];
            var discount = $(this).data("discount") ?? null;
            discountPercenteng = $(this).data("percenteng") || 0;
            var unit = $(this).data("unit") || null;
            var productBasePrice = parseFloat($(this).data("base-price")) || parseFloat($(this).data("price")) || 0;

            // Robust bypass logic: ignore unit for bypass decision
            var isBypass = isEmptyVariation(sizes) && isEmptyVariation(weights) && isEmptyVariation(additions);

            if (isBypass) {
                var finalPrice = parseFloat($(this).data("price")) || productBasePrice;
                var qty = $("#product_quantity").val() || 1;

                // Pass unit if available, using qty as amount
                var uAmount = (unit && unit.toString().trim() !== '') ? qty : null;
                var uType = (unit && unit.toString().trim() !== '') ? unit : null;

                performAddToCart(productId, qty, uAmount, uType, null, null, null, [], finalPrice)
                    .done(function (data) {
                        showCartSuccess(data);
                    })
                    .fail(function (xhr) {
                        let msg = "Something went wrong!";
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        }
                        Swal.fire({ icon: "error", title: msg });
                    });
                return;
            }

            // ... (rest of modal population logic)

            // reset flag for each modal open
            optionsHaveDiscount = false;


            $("#sizeModalLabel").text(`${localizedText.selectSize}`);

            // Clear all option containers before populating
            var sizeOptionsContainer = $("#sizeOptionsContainer");
            var weightOptionsContainer = $("#weightOptionsContainer");
            var additionOptionsContainer = $("#additionOptionsContainer");

            sizeOptionsContainer.empty();
            weightOptionsContainer.empty();
            additionOptionsContainer.empty();

            // Reset visibility of all sections
            $("#sizeOptionsSection").hide();
            $("#weightOptionsSection").hide();
            $("#additionOptionsSection").hide();
            $("#unitDisplaySection").hide();

            // Display unit information and allow entering amount if available
            if (unit && unit.trim() !== '') {
                $("#unitDisplayValue").text(unit);
                // configure input depending on unit type (KG allows decimals, PC integer)
                var unitLower = unit.toLowerCase();
                var $unitInput = $("#unitAmount");
                if (unitLower.indexOf('kg') !== -1 || unitLower.indexOf('k g') !== -1) {
                    $unitInput.attr('step', '0.001');
                    $unitInput.attr('min', '0.001');
                    $unitInput.val('1');
                } else if (unitLower.indexOf('pc') !== -1 || unitLower.indexOf('piece') !== -1) {
                    $unitInput.attr('step', '1');
                    $unitInput.attr('min', '1');
                    $unitInput.val('1');
                } else {
                    // default to integer quantity
                    $unitInput.attr('step', '1');
                    $unitInput.attr('min', '1');
                    $unitInput.val('1');
                }
                $("#unitDisplaySection").show();
                // Show submit button when unit is present
                $("#submitSelection").show();
            } else {
                $("#unitDisplaySection").hide();
            }


            // Populate sizes (apply discount percentage to size price when present)
            if (sizes.length > 0) {
                sizes.forEach(function (size) {
                    // determine base price (Additive: Product Base + Option Extra)
                    var optionAdditional = 0;
                    if (size && size.pivot && size.pivot.price) {
                        optionAdditional = parseFloat(size.pivot.price) || 0;
                    }

                    // New Logic: Base Price = Product Base + Option
                    var basePrice = productBasePrice + optionAdditional;

                    // Fallback logic if needed (though productBasePrice should always exist)
                    if (basePrice === 0 && discount) {
                        basePrice = parseFloat(discount) || 0;
                    }

                    var percent = parseFloat(discountPercenteng) || 0;
                    var discountedPrice = basePrice;
                    if (percent > 0 && basePrice > 0) {
                        discountedPrice = basePrice - (percent / 100 * basePrice);
                        // mark that modal options already include discount
                        optionsHaveDiscount = true;
                    }

                    // labels from size object (guarding several possible key names)
                    var enLabel = size && (size.Size || size.size || size.name) ? (size.Size || size.size || size.name) : '';
                    var arLabel = size && (size.Size_ar || size.size_ar || size.name_ar) ? (size.Size_ar || size.size_ar || size.name_ar) : '';

                    var label = '';
                    if (locale === 'ar') {
                        if (arLabel && enLabel && arLabel !== enLabel) {
                            label = arLabel + ' / ' + enLabel;
                        } else {
                            label = arLabel || enLabel || 'N/A';
                        }
                    } else {
                        if (enLabel && arLabel && arLabel !== enLabel) {
                            label = enLabel + ' / ' + arLabel;
                        } else {
                            label = enLabel || arLabel || 'N/A';
                        }
                    }

                    var displayPrice = parseFloat(discountedPrice).toFixed(3);

                    var sizeOption = `<button class="btn btn-outline-primary size-option" data-product-id="${productId}" data-size-id="${size.id}" data-price="${discountedPrice}" data-base-price="${basePrice}">
                            ${label} - ${displayPrice} OMR
                        </button>`;
                    sizeOptionsContainer.append(sizeOption);
                });
                $("#sizeOptionsSection").show();
            }

            // Populate weights (apply discount percent to weight price when present)
            if (weights.length > 0) {
                weights.forEach(function (weight) {
                    var baseW = parseFloat(weight.price) || 0;
                    var percentW = parseFloat(discountPercenteng) || 0;
                    var discountedW = baseW;
                    if (percentW > 0 && baseW > 0) {
                        discountedW = baseW - (percentW / 100 * baseW);
                        optionsHaveDiscount = true;
                    }
                    var displayW = parseFloat(discountedW).toFixed(3).replace(/\.?0+$/, '');
                    var weightOption = `
                            <button class="btn btn-outline-primary weight-option"
                                data-product-id="${productId}"
                                data-weight-id="${weight.id}"
                                data-price="${discountedW}"
                                data-base-price="${baseW}"
                                data-percenteng="${discountPercenteng}">
                                ${weight.weight} ${localizedText.grams} - ${displayW} OMR
                            </button>`;
                    weightOptionsContainer.append(weightOption);
                });
                $("#weightOptionsSection").show();
            }

            // Populate additions
            if (additions.length > 0) {
                additions.forEach(function (addition) {
                    var additionOption = `<label class="addition-option">
                            <input type="checkbox" data-addition-id="${addition.id
                        }" data-price="${addition.price}">
                            <span class="checkmark"></span>
                            ${locale === "en"
                            ? addition.name
                            : addition.name_ar
                        } - ${addition.price} + OMR
                        </label>`;
                    additionOptionsContainer.append(additionOption);
                });
                $("#additionOptionsSection").show();
            }

            $("#sizeModal").modal("show");
        });

        // var selectedSizeId = null;
        // var selectedSizePrice = 0;
        // var selectedAdditions = [];

        $(document).on("click", ".size-option", function () {
            $(".size-option").removeClass("selected");
            $(this).addClass("selected");
            selectedSizeId = $(this).data("size-id");
            // prefer the data-price (may already be discounted)
            selectedSizePrice = parseFloat($(this).data("price")) || 0;
            // if modal options were not pre-discounted, apply discount to base-price
            if (!optionsHaveDiscount) {
                var base = parseFloat($(this).data('base-price')) || selectedSizePrice;
                if (discountPercenteng > 0 && base > 0) {
                    selectedSizePrice = base - (discountPercenteng / 100 * base);
                }
            }
        });

        $(document).on("click", ".weight-option", function () {
            $("#submitSelection").show();
            $(".weight-option").removeClass("selected");
            $(this).addClass("selected");
            selectedWeightId = $(this).data("weight-id");
            selectedWeightPrice = parseFloat($(this).data("price")) || 0;
            discountPercenteng = $(this).data("percenteng") || discountPercenteng;
            if (!optionsHaveDiscount) {
                var baseW = parseFloat($(this).data('base-price')) || selectedWeightPrice;
                if (discountPercenteng > 0 && baseW > 0) {
                    selectedWeightPrice = baseW - (discountPercenteng / 100 * baseW);
                }
            }
        });

        $(document).on("change", ".addition-option input", function () {
            var additionId = $(this).data("addition-id");
            var additionPrice = parseFloat($(this).data("price"));

            if (!isProductDetailsPage) {
                if ($(this).is(":checked")) {
                    selectedAdditions.push({
                        id: additionId,
                        price: additionPrice,
                    });
                } else {
                    selectedAdditions = selectedAdditions.filter(function (
                        addition
                    ) {
                        return addition.id !== additionId;
                    });
                }
            }
        });

        $("#submitSelection").on("click", function () {
            const productId = selectedProductId;

            // VALIDATION: If sizes are present (section visible) but no size selected, prevent add to cart
            if ($("#sizeOptionsSection").is(":visible") && !selectedSizeId) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer);
                        toast.addEventListener("mouseleave", Swal.resumeTimer);
                    },
                });
                Toast.fire({ icon: "warning", title: localizedText.selectSize }); // Reuse existing localized text or "Please select an option"
                return;
            }

            var basePrice = selectedSizePrice + selectedWeightPrice;

            // If no size/weight selected, use the product's base price from the button that opened the modal
            if (basePrice === 0) {
                var $triggerBtn = $('.add-cart[data-id="' + productId + '"]').first();
                // Prefer base-price (original) so discount logic below works correctly. 
                // If only price (discounted) is available, user likely assumes that's the base.
                var priceFromBtn = parseFloat($triggerBtn.data('base-price')) || parseFloat($triggerBtn.data('price')) || 0;
                basePrice = priceFromBtn;
            }

            // If modal options already include discount, do not re-apply discount here.
            if (!optionsHaveDiscount && discountPercenteng > 0 && basePrice > 0) {
                var discountAmount = (discountPercenteng / 100) * basePrice;
                basePrice = basePrice - discountAmount;
            }

            var totalPrice = basePrice;

            console.log(discountPercenteng);

            selectedAdditions.forEach(function (addition) {
                totalPrice += addition.price;
            });

            // determine quantity: if a unit amount was entered use that (KG may be decimal)
            var quantity = $("#product_quantity").val();
            var unitAmountVal = $("#unitAmount").length ? $("#unitAmount").val() : null;
            if (unitAmountVal && unitAmountVal !== '' && $("#unitDisplaySection").is(":visible")) {
                // check unit type from displayed text
                var unitText = $("#unitDisplayValue").text().toLowerCase();
                if (unitText.indexOf('kg') !== -1 || unitText.indexOf('k g') !== -1) {
                    quantity = parseFloat(unitAmountVal) || 1;
                } else {
                    quantity = parseInt(unitAmountVal) && parseInt(unitAmountVal) > 0 ? parseInt(unitAmountVal) : 1;
                }
                // Multiply base price by unit quantity for proper total
                totalPrice = totalPrice * quantity;
            } else {
                // fallback to integer quantity from the page
                quantity = parseInt(quantity) && parseInt(quantity) > 0 ? parseInt(quantity) : 1;
            }

            $("#sizeModal").modal("hide");
            let colorSelector = document.querySelector(
                'input[name="productColor"]:checked'
            );

            let color;
            if (colorSelector) {
                color = colorSelector.value;
            } else {
                color = null;
            }

            performAddToCart(
                productId,
                quantity,
                (function () {
                    var v = $('#unitAmount').length ? $('#unitAmount').val() : null; return v ? v : null;
                })(),
                (function () { var t = $('#unitDisplayValue').text() || null; return t ? t : null; })(),
                color,
                selectedSizeId,
                selectedWeightId,
                selectedAdditions.map(function (addition) {
                    return addition.id;
                }),
                totalPrice
            ).done(function (data) {
                showCartSuccess(data);
            }).fail(function (xhr) {
                let msg = "Something went wrong!";
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                    msg = xhr.responseJSON.error;
                }
                Swal.fire({
                    icon: "error",
                    title: msg,
                    confirmButtonText: "OK"
                });
            });
        });
    });

    // Show the Add to Cart button when a size is selected
    $(".sizeSelect").on("change", function () {
        var productId = $(this).data("product-id");
        var selectedSize = $(this).val();
        if (selectedSize) {
            $('.addCart[data-id="' + productId + '"]').show();
        } else {
            $('.addCart[data-id="' + productId + '"]').hide();
        }
    });

    // Make functions global so they can be called from Blade views
    window.currencyPrice = function (price) {
        let result = 0;
        $.ajax({
            url: $("#currency-price-url").data("url"),
            method: "POST",
            async: false,
            data: {
                price: price,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (data) {
                result = data;
            },
        });
        return result;
    }

    // Recalculate and update the displayed final total (subtotal - coupon)
    window.recalcFinalTotal = function (subtotal) {
        var coupon = parseFloat($('#CartCouponAmount').data('amount')) || 0;
        var sub = parseFloat(subtotal) || 0;
        var finalTotal = sub - coupon;
        if (finalTotal < 0) finalTotal = 0;
        $('.cart-page-final-total').html(window.currencyPrice(finalTotal));
    }

    window.currencySymbol = function () {
        let result = 0;
        $.ajax({
            url: $("#currency-symbol-url").data("url"),
            method: "GET",
            async: false,
            success: function (data) {
                result = data;
            },
        });
        return result;
    }
    //cart decrease
    $(document).on("click", ".qty_decrease", function () {
        if (isCartUpdating) return;
        let id = $(this).attr("data-id");
        let $this = $(this);
        let $input = $this.parent().find(".qty_value");
        let quantity = parseInt($input.val()) || 1;

        if (quantity > 1) {
            isCartUpdating = true;
            $this.prop('disabled', true);
            $this.siblings().prop('disabled', true);

            // Optimistic update
            $input.val(quantity - 1);

            $.ajax({
                method: "GET",
                url: $("#CartDecrementFromSession").data("url"),
                data: {
                    id: id,
                    quantity: quantity,
                },
                success: (data) => {
                    // Update summary counts and recalc final total
                    $(".totalCountItem").html(data[0]);
                    $(".totalAmount").html(data.total_amount_formatted || currencyPrice(data[1]));
                    recalcFinalTotal(data[1]);

                    var obj = data[2] || {};
                    var $container = $this.closest('tr, .card-body, .cart-card, .single-grid-product, .product-item');

                    if (data.subtotal_formatted) {
                        $container.find('.SubTotalAmount').html(data.subtotal_formatted);
                    } else if (typeof data[3] !== 'undefined') {
                        $container.find('.SubTotalAmount').html(currencyPrice(data[3]));
                    }

                    if (obj && obj[id]) {
                        var item = obj[id];
                        $container.find('.qty_value').val(item.qty);
                    }

                    // Note: Skipping full side-cart re-render for speed unless strictly necessary.
                    // If you need side-cart sync, use a debounced update or lighter logic.
                },
                complete: () => {
                    isCartUpdating = false;
                    $this.prop('disabled', false);
                    $this.siblings().prop('disabled', false);
                }
            });
        }
    });

    //cart increase
    $(document).on("click", ".qty_increase", function () {
        if (isCartUpdating) return;
        let id = $(this).attr("data-id");
        let $this = $(this);
        let $input = $this.parent().find(".qty_value");
        let quantity2 = parseInt($input.val()) || 1;

        isCartUpdating = true;
        $this.prop('disabled', true);
        $this.siblings().prop('disabled', true);

        // Optimistic update
        $input.val(quantity2 + 1);

        $.ajax({
            method: "GET",
            url: $("#CartIncrementFromSession").data("url"),
            data: {
                id: id,
                quantity: quantity2,
            },
            success: (data) => {
                // Update totals and counts
                $(".totalCountItem").html(data[0]);
                $(".totalAmount").html(data.total_amount_formatted || currencyPrice(data[1]));
                recalcFinalTotal(data[1]);

                var obj = data[2] || {};
                var $container = $this.closest('tr, .card-body, .cart-card, .single-grid-product, .product-item');

                if (data.subtotal_formatted) {
                    $container.find('.SubTotalAmount').html(data.subtotal_formatted);
                } else if (typeof data[3] !== 'undefined') {
                    $container.find('.SubTotalAmount').html(currencyPrice(data[3]));
                }

                if (obj && obj[id]) {
                    var item = obj[id];
                    $container.find('.qty_value').val(item.qty);
                }
            },
            complete: () => {
                isCartUpdating = false;
                $this.prop('disabled', false);
                $this.siblings().prop('disabled', false);
            }
        });
    });

    $(document).on("click", ".deleteItem", function () {
        let id = $(this).attr("data-id");
        $.ajax({
            method: "GET",
            url: $("#CartDeleteFromSession").data("url"),
            data: {
                id: id,
            },
            success: function (data) {
                // let currsym = currencySymbol();
                $(".totalCountItem").html(data[0]);
                $(".totalAmount").html(data.total_amount_formatted || currencyPrice(data[1]));
                recalcFinalTotal(data[1]);
                let Img = $("#productImgAsset").data("url");
                let obj = data[2];
                let bodyData = "";
                let bodyArray = [];
                let i = 1;
                Object.keys(obj).forEach(function (key) {
                    bodyData =
                        '<div class="product-item cart-product-item"><div class="single-grid-product"><div class="product-top"><a href="#"><img class="product-thumbnal" src="' +
                        Img +
                        "/" +
                        obj[key]["options"]["image"] +
                        '" alt="cart"></a></div><div class="product-info"><div class="product-name-part"><h3 class="product-name"><a class="product-link" href="#">' +
                        obj[key]["name"] +
                        '</a></h3><div class="cart-quantity input-group"><div class="increase-btn dec qtybutton btn qty_decrease" data-id="' +
                        obj[key]["rowId"] +
                        '">-</div><input class="qty-input cart-plus-minus-box qty_value" type="text" name="qtybutton" id="qty_value" value="' +
                        obj[key]["qty"] +
                        '" readonly /><div class="increase-btn inc qtybutton btn qty_increase" data-id="' +
                        obj[key]["rowId"] +
                        '">+</div></div><button class="cart-remove-btn deleteItem" data-id="' +
                        obj[key]["rowId"] +
                        '">Remove</button></div><div class="product-price"><span class="regular-price mr-0">' +
                        currencyPrice(obj[key]["weight"] * obj[key]["qty"]) +
                        '</span><span class="price">' +
                        currencyPrice(obj[key]["price"] * obj[key]["qty"]) +
                        "</span></div></div></div></div>";
                    bodyArray.push(bodyData);
                });
                $("#bodyData").html(bodyArray);
                let Toast = Swal.mixin({
                    toast: true,
                    position: "center",
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'premium-toast-glass'
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Cart Product Removed",
                });
            },
        });
    });

    $(document).on("click", ".deleteItemCart", function () {
        let id = $(this).attr("data-id");
        $.ajax({
            method: "GET",
            url: $("#CartDeleteFromSession").data("url"),
            data: {
                id: id,
            },
            success: function (data) {
                window.location.reload();
            },
        });
    });
})(jQuery);
