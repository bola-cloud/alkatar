require("./bootstrap");
require("flowbite");

$(document).ready(function () {
    $(".sizeSelect").on("change", function() {
        // Find the closest product container
        var productCard = $(this).closest(".single-grid-product");
        // Get the selected size
        var selectedSize = $(this).val();

        // Get the selected price
        var selectedPrice = $(this)
            .find(":selected")
            .text()
            .split("-")[1]
            .trim();

        // Remove OMR from the price
        selectedPrice = selectedPrice.replace("OMR", "").replace(" ", "");

        // Find the add to cart button within the same product card
        var addCartButton = productCard.find(".addCart");

        // Update the data-selected-price attribute and show the add to cart button
        addCartButton.attr("data-selected-price", selectedPrice).show();
        addCartButton.attr("data-selected-size", selectedSize);
    });

});
