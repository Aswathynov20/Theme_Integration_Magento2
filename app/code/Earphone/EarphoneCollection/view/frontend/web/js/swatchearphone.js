jQuery(document).ready(function($) {
    var selectedProductId = null;

    // Handle swatch clicks
    $(document).on('click', '.circular-swatch-earphone', function(event) {
        event.stopPropagation(); // Stop the event from propagating up the DOM
        var colorSwatch = $(this);
        var entityId = colorSwatch.data('entity-id');
        var formKeyFinal = colorSwatch.data("form-key");
        var color = colorSwatch.data('attribute-id');
        var price = colorSwatch.data('price');
        var productImage = colorSwatch.closest(".product-item").find(".product-image-photo");
        var swatchElement = $(this);

        var priceContainer = swatchElement
            .closest(".product-item")
            .find(".price-wrapper");

        selectedProductId = entityId;

        // Update image
        $.ajax({
            url: "/swatches/ajax/media/?product_id=" + entityId + "&isAjax=true",
            method: 'GET',
            data: {
                entityId: entityId,
                color: color,
                form_key: formKeyFinal,
            },
            success: function(response) {
                var imageUrl = response.large; // Assuming the response contains the image URL
                productImage.attr('src', imageUrl);

                // Update the displayed price for the selected product only
                priceContainer.find(".price").text('₹' + price);
            },
            error: function(error) {
                console.error("Error fetching image:", error);
            }
        });

        // Visually indicate selection (optional)
        $('.circular-swatch-earphone').removeClass('selected'); // Remove 'selected' class from all swatches
        colorSwatch.addClass('selected');
    });

    // Handle add-to-cart clicks
    $(".tocart").click(function (e) {
        if (selectedProductId === null) {
            e.preventDefault();
            // alert("Please select a product");
            $(this)
                .closest(".product-item-actions")
                .find(".error-message")
                .show();

            setTimeout(function () {
                $(".error-message").hide();
            }, 5000);
            return; // Stop further execution
        }
        var addToCartUrl =
            "/checkout/cart/add/product/" + selectedProductId + "/";
        console.log("Add to Cart URL:", addToCartUrl);

        // Your AJAX call to add product to cart
        $.ajax({
            url: addToCartUrl,
            type: "POST",
            data: { form_key: $('input[name="form_key"]').val() }, // Make sure you get the correct form key value
            success: function (response) {
                console.log("Response:", response);
                // Redirect to cart or perform other actions upon successful addition
                // if (selectedProductId !== null) {
                window.location.href = "/checkout/cart";
                // }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            },
        });
       
    });
});
