require(['jquery'], function ($) {
    $(document).on('click', '.towishlist', function () {
        // Get product ID and form key from data attributes
        var productId = $(this).data('product-id');
        var formKey = $(this).data('form-key');

        // Log the values for debugging
        console.log('Product ID:', productId);
        console.log('Form Key:', formKey);

        // Check if productId and formKey are valid
        if (productId && formKey) {
            // Make an AJAX request to add the product to the wishlist
            $.ajax({
                url: '/wishlist/index/add/product/' + productId + "/",
                type: 'POST',
                // dataType: 'json',
                data: {
                    productId: productId,
                    form_key: formKey
                },
                success: function (response) {
                    // Check if the server response indicates success
                    console.log("jdjs");
                    // window.location.href ="/wishlist/index/index";
                    // if (response.success) {
                    //     // Check if there is a backUrl for redirection
                    //     if (response.backUrl) {
                    //         window.location.href = response.backUrl;
                    //     } else {
                    //         alert('Product added to wishlist successfully.');
                    //     }
                    // } else {
                    //     // Product addition to wishlist failed
                    //     alert('Error: ' + response.error);
                    // }
                },
                error: function (xhr, status, error) {
                    // AJAX request failed
                    alert('Error occurred while adding the product to wishlist: ' + error);
                },
            });
        } else {
            // Display an alert for invalid product ID or form key
            alert('Invalid product ID or form key.');
        }
    });
});
