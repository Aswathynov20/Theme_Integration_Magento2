<?php
// app/code/FirstAPI/APIFirst/Model/WishlistRepository.php

namespace FirstAPI\APIFirst\Model;

use FirstAPI\APIFirst\Api\WishlistInterface;
use Magento\Wishlist\Model\WishlistFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use FirstAPI\APIFirst\Model\ResourceModel\Wishlist as WishlistResourceModel;

class WishlistRepository implements WishlistInterface
{
    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var WishlistResourceModel
     */
    private $wishlistResourceModel;

    public function __construct(
        WishlistFactory $wishlistFactory,
        ProductRepositoryInterface $productRepository,
        WishlistResourceModel $wishlistResourceModel
    ) {
        $this->wishlistFactory = $wishlistFactory;
        $this->productRepository = $productRepository;
        $this->wishlistResourceModel = $wishlistResourceModel;
    }

    /**
     * Load customer's wishlist by customer ID.
     *
     * @param int $customerId
     * @return \Magento\Wishlist\Model\Wishlist
     */
    private function loadWishlistByCustomerId($customerId)
    {
        return $this->wishlistFactory->create()->loadByCustomerId($customerId, true);
    }

    /**
     * Get wishlist product details for a given customer.
     *
     * @param int $customerId
     * @return array
     */
    public function getWishlistDetails($customerId)
    {
        $wishlist = $this->loadWishlistByCustomerId($customerId);
        $wishlistItems = $wishlist->getItemCollection()->getData();
        return $wishlistItems;
    }
/**
 * Add a product to the customer's wishlist.
 *
 * @param int $customerId
 * @param int $productId
 * @return bool|string
 */
public function addProductToWishlist($customerId, $productId)
{
    // Load product by its ID
    $product = $this->productRepository->getById($productId);

    $wishlist = $this->loadWishlistByCustomerId($customerId);

    // Check if the product already exists in the wishlist
    $wishlistItems = $wishlist->getItemCollection();
    foreach ($wishlistItems as $item) {
        if ($item->getProductId() == $productId) {
            return "Product '{$product->getName()}' already exists in the wishlist.";
        }
    }

    // If the product doesn't exist in the wishlist, add it
    $wishlist->addNewItem($product);

    // Save the wishlist using the resource model
    $this->wishlistResourceModel->save($wishlist);

    return "Product '{$product->getName()}' added successfully to the wishlist.";
}
}