<?php
namespace FirstAPI\APIFirst\Api;

interface WishlistManagementInterface
{
    /**
     * Add item to customer's wishlist.
     *
     * @param int $customerId
     * @param int $productId
     * @param array $productDetails
     * @return bool
     */
    public function addItemToWishlist($customerId, $productId, $productDetails);
}

