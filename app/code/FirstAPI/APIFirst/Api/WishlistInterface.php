<?php
namespace FirstAPI\APIFirst\Api;

interface WishlistInterface
{
    /**
     * Get wishlist product details for a given customer.
     *
     * @param int $customerId
     * @return array
     */
    public function getWishlistDetails($customerId);

    /**
     * Add a product to the customer's wishlist.
     *
     * @param int $customerId
     * @param mixed $productId
     * @return bool
     */
    public function addProductToWishlist($customerId, $productId);
}
