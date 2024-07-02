(function ($) {
    "use strict";

    let sizeid;
    var $loading = $("#loadingDiv").hide();
    $(".MyWishList").on("click", function () {
        let product_id = $(this).attr("data-id");
        $.ajax({
            url: $("#productWishlistUrl").data("url"),
            method: "get",
            data: {
                product_id: product_id,
            },
            success: function (data) {
                if (data.status === 0) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "warning",
                        title: data.message,
                    });
                } else if (data.status === 1) {
                    $(".wishListCuntFromController").html(
                        data.wishlist_count + " Items"
                    );
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: data.message,
                    });
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Something went wrong!",
                    });
                }
            },
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
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "warning",
                        title: data.message,
                    });
                } else if (data.status === 1) {
                    $(".CompareCuntFromController").html(
                        data.compare_count + " Items"
                    );
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: data.message,
                    });
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Something went wrong!",
                    });
                }
            },
        });
    });

    $(document).ready(function () {
        var isProductDetailsPage = $(".product-single-area").length > 0;
        let selectedProductId = null;


        if (isProductDetailsPage) {
            $(".addCart").on("click", function () {
                var productId = $(this).data("product-id");
                var sizeId = $(this).attr("data-size-id");
                var price = $(this).attr("data-price");
                var quantity = $("#product_quantity").val();
                var colorId = $('input[name="productColor"]:checked').val();
                console.log("quanity", quantity);

                $.ajax({
                    url: $("#AddToCartIntoSession").data("url"),
                    method: "POST",
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        color_id: colorId,
                        size_id: sizeId,
                        additions: selectedAdditions.map(addition => addition.id),
                        selectedSize: selectedSizeId,
                        price: price,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (data) {
                        $(".totalCountItem").html(data[0]);
                        $(".totalAmount").html(currencyPrice(data[1]));
                        // let Img = $("#productImgAsset").data("url");
                        // let obj = data[2];
                        // let bodyData = "";
                        // let bodyArray = [];
                        // let i = 1;
                        // Object.keys(obj).forEach(function (key) {
                        //     bodyData =
                        //         '<div class="product-item cart-product-item"><div class="single-grid-product"><div class="product-top"><a href="#"><img class="product-thumbnal" src="' +
                        //         Img +
                        //         "/" +
                        //         obj[key]["options"]["image"] +
                        //         '" alt="cart"></a></div><div class="product-info"><div class="product-name-part"><h3 class="product-name"><a class="product-link" href="#">' +
                        //         obj[key]["name"] +
                        //         '</a></h3><div class="cart-quantity input-group"><div class="increase-btn dec qtybutton btn qty_decrease" data-id="' +
                        //         obj[key]["rowId"] +
                        //         '">-</div><input class="qty-input cart-plus-minus-box qty_value" type="text" name="qtybutton" id="qty_value" value="' +
                        //         obj[key]["qty"] +
                        //         '" readonly /><div class="increase-btn inc qtybutton btn qty_increase" data-id="' +
                        //         obj[key]["rowId"] +
                        //         '">+</div></div><button class="cart-remove-btn deleteItem" data-id="' +
                        //         obj[key]["rowId"] +
                        //         '">Remove</button></div><div class="product-price"><span class="regular-price mr-0">' +
                        //         currencyPrice(
                        //             obj[key]["weight"] * obj[key]["qty"]
                        //         ) +
                        //         '</span><span class="price">' +
                        //         currencyPrice(
                        //             obj[key]["price"] * obj[key]["qty"]
                        //         ) +
                        //         "</span></div></div></div></div>";
                        //     bodyArray.push(bodyData);
                        // });
                        // $("#bodyData").html(bodyArray);

                        const Toast = Swal.mixin({
                            toast: true,
                            position: "bottom-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener(
                                    "mouseenter",
                                    Swal.stopTimer
                                );
                                toast.addEventListener(
                                    "mouseleave",
                                    Swal.resumeTimer
                                );
                            },
                        });
                        Toast.fire({
                            icon: "success",
                            title: localizedText.productAddedToCart,
                            position: "top-end",
                        });
                    },
                });
            });
            // $(".addCart").on("click", function () {
            //     $(".single-size").on("click", function () {
            //         var sizeRadio = $(this).find(".size-radio");
            //         sizeRadio.prop("checked", true);
            //         var newPrice = parseFloat(sizeRadio.val());
            //         $(".product-price .price").text("OMR " + newPrice.toFixed(2));
            //         $(".addCart").attr("data-price", sizeRadio.val());
            //         $(".addCart").attr("data-size-id", sizeRadio.data("size"));
            //     });
            // });
        } else {

            $(".addCart").on("click", function () {
                var productId = $(this).data("id");
                selectedProductId = productId;
                var productName = $(this).data("name");
                var sizes = $(this).data("sizes");
                var additions = $(this).data("additions") || [];

                $("#sizeModalLabel").text(`${localizedText.selectSize}`);

                var sizeOptionsContainer = $("#sizeOptionsContainer");
                sizeOptionsContainer.empty();

                sizes.forEach(function (size) {
                    var sizeOption = `<button class="btn btn-outline-primary size-option" data-product-id="${productId}" data-size-id="${size.id}" data-price="${size.pivot.price}">
                                        ${size.Size} - ${size.pivot.price}
                                      </button>`;
                    sizeOptionsContainer.append(sizeOption);
                });

                var additionOptionsContainer = $("#additionOptionsContainer");
                var additionOptionsSection = $("#additionOptionsSection");
                additionOptionsContainer.empty();

                if (additions.length > 0) {
                    additions.forEach(function (addition) {
                        var additionOption = `<label class="addition-option">
                                                <input type="checkbox" data-addition-id="${addition.id}" data-price="${addition.price}">
                                                <span class="checkmark"></span>
                                                ${addition.name_ar} - ${addition.price}
                                              </label>`;
                        additionOptionsContainer.append(additionOption);
                    });

                    additionOptionsSection.show();
                } else {
                    additionOptionsSection.hide();
                }

                $("#sizeModal").modal("show");
            });
        }

        var selectedSizeId = null;
        var selectedSizePrice = 0;
        var selectedAdditions = [];

        $(document).on("click", ".size-option", function () {
            $(".size-option").removeClass("selected");
            $(this).addClass("selected");
            selectedSizeId = $(this).data("size-id");
            selectedSizePrice = parseFloat($(this).data("price"));
        });

        $(document).on("change", ".addition-option input", function () {
            var additionId = $(this).data("addition-id");
            var additionPrice = parseFloat($(this).data("price"));

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
        });

        $("#submitSelection").on("click", function () {
            const productId = selectedProductId

            var totalPrice = selectedSizePrice;
            selectedAdditions.forEach(function (addition) {
                totalPrice += addition.price;
            });

            $("#sizeModal").modal("hide");

            var quantity = $("#product_quantity").val();
            let colorSelector = document.querySelector(
                'input[name="productColor"]:checked'
            );

            let color;
            if (colorSelector) {
                color = colorSelector.value;
            } else {
                color = null;
            }

            $.ajax({
                url: $("#AddToCartIntoSession").data("url"),
                method: "POST",
                data: {
                    product_id: productId,
                    quantity: quantity,
                    color_id: color,
                    size_id: selectedSizeId,
                    additions: selectedAdditions.map(function (addition) {
                        return addition.id;
                    }),
                    selectedSize: selectedSizeId,
                    price: totalPrice,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    $(".totalCountItem").html(data[0]);
                    $(".totalAmount").html(currencyPrice(data[1]));
                    // let Img = $("#productImgAsset").data("url");
                    // let obj = data[2];
                    // let bodyData = "";
                    // let bodyArray = [];
                    // let i = 1;
                    // Object.keys(obj).forEach(function (key) {
                    //     bodyData =
                    //         '<div class="product-item cart-product-item"><div class="single-grid-product"><div class="product-top"><a href="#"><img class="product-thumbnal" src="' +
                    //         Img +
                    //         "/" +
                    //         obj[key]["options"]["image"] +
                    //         '" alt="cart"></a></div><div class="product-info"><div class="product-name-part"><h3 class="product-name"><a class="product-link" href="#">' +
                    //         obj[key]["name"] +
                    //         '</a></h3><div class="cart-quantity input-group"><div class="increase-btn dec qtybutton btn qty_decrease" data-id="' +
                    //         obj[key]["rowId"] +
                    //         '">-</div><input class="qty-input cart-plus-minus-box qty_value" type="text" name="qtybutton" id="qty_value" value="' +
                    //         obj[key]["qty"] +
                    //         '" readonly /><div class="increase-btn inc qtybutton btn qty_increase" data-id="' +
                    //         obj[key]["rowId"] +
                    //         '">+</div></div><button class="cart-remove-btn deleteItem" data-id="' +
                    //         obj[key]["rowId"] +
                    //         '">Remove</button></div><div class="product-price"><span class="regular-price mr-0">' +
                    //         currencyPrice(
                    //             obj[key]["weight"] * obj[key]["qty"]
                    //         ) +
                    //         '</span><span class="price">' +
                    //         currencyPrice(obj[key]["price"] * obj[key]["qty"]) +
                    //         "</span></div></div></div></div>";
                    //     bodyArray.push(bodyData);
                    // });
                    // $("#bodyData").html(bodyArray);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: localizedText.productAddedToCart,
                        position: "top-end",
                    });
                },
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

    function currencyPrice(price) {
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
    function currencySymbol() {
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
        let id = $(this).attr("data-id");
        let $this = $(this);
        let quantity = $this.parent().find(".qty_value").val();
        if (quantity >= 1) {
            $.ajax({
                method: "GET",
                url: $("#CartDecrementFromSession").data("url"),
                data: {
                    id: id,
                    quantity: quantity,
                },
                success: (data) => {
                    // let currsym = currencySymbol();
                    $(this)
                        .closest("tr")
                        .find(".SubTotalAmount")
                        .html(currencyPrice(data[3]));
                    $(".totalCountItem").html(data[0]);
                    $(".totalAmount").html(currencyPrice(data[1]));
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
                            currencyPrice(
                                obj[key]["weight"] * obj[key]["qty"]
                            ) +
                            '</span><span class="price">' +
                            currencyPrice(obj[key]["price"] * obj[key]["qty"]) +
                            "</span></div></div></div></div>";
                        bodyArray.push(bodyData);
                    });
                    $("#bodyData").html(bodyArray);
                    let Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener(
                                "mouseenter",
                                Swal.stopTimer
                            );
                            toast.addEventListener(
                                "mouseleave",
                                Swal.resumeTimer
                            );
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Cart Quantity Decrement",
                    });
                },
            });
        } else {
            let Toast = Swal.mixin({
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
            Toast.fire({
                icon: "error",
                title: "Please Click Remove Button",
            });
        }
    });
    //cart increase
    $(document).on("click", ".qty_increase", function () {
        let id = $(this).attr("data-id");
        let $this = $(this);
        let quantity2 = $this.parent().find(".qty_value").val();
        $.ajax({
            method: "GET",
            url: $("#CartIncrementFromSession").data("url"),
            data: {
                id: id,
                quantity: quantity2,
            },
            success: (data) => {
                // let currsym = currencySymbol();
                $(this)
                    .closest("tr")
                    .find(".SubTotalAmount")
                    .html(currencyPrice(data[3]));
                $(".totalCountItem").html(data[0]);
                $(".totalAmount").html(currencyPrice(data[1]));
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
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer);
                        toast.addEventListener("mouseleave", Swal.resumeTimer);
                    },
                });
                Toast.fire({
                    icon: "success",
                    title: "Cart Quantity Increment",
                });
            },
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
                $(".totalAmount").html(currencyPrice(data[1]));
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
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer);
                        toast.addEventListener("mouseleave", Swal.resumeTimer);
                    },
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
