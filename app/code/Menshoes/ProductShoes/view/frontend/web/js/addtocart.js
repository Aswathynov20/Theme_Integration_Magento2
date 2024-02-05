require(['jquery'], function ($) {
    $('.add-to-cart-btn').on('click', function () {
        var productId = $(this).data('product-id');
        console.log(productId);
        var formKey = $(this).data('form-key');
        console.log(formKey);
        var addToCartUrl = $(this).data('add-to-cart-url');
        console.log(addToCartUrl);
        $.ajax({
            type: 'POST',
            url: addToCartUrl,
            data: {
                product: productId,
                form_key: formKey
            },
            success: function (response) {
                // Handle success, e.g., show a success message
                console.log('Product added to cart successfully!');
            },
            error: function (xhr, status, error) {
                // Handle errors, e.g., display an error message
                console.error('Error adding product to cart:', error);
            }
        });
    });
});