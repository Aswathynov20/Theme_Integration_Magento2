<?php

namespace Menshoes\ProductShoes\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Framework\Data\Form\FormKey;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;

class Product extends Template
{
    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var WishlistHelper
     */
    protected $wishlistHelper;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ConfigurableType
     */
    protected $configurableType;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Product constructor.
     * @param Template\Context $context
     * @param ProductCollectionFactory $productCollectionFactory
     * @param CategoryFactory $categoryFactory
     * @param WishlistHelper $wishlistHelper
     * @param FormKey $formKey
     * @param StoreManagerInterface $storeManager
     * @param ConfigurableType $configurableType
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductCollectionFactory $productCollectionFactory,
        CategoryFactory $categoryFactory,
        WishlistHelper $wishlistHelper,
        FormKey $formKey,
        StoreManagerInterface $storeManager,
        ConfigurableType $configurableType,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->wishlistHelper = $wishlistHelper;
        $this->formKey = $formKey;
        $this->storeManager = $storeManager;
        $this->configurableType = $configurableType;
        $this->productRepository = $productRepository;
    }

    /**
     * Get product collection filtered by category
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        // Get category ID
        $categoryId = 90;

        // Get category model
        $category = $this->categoryFactory->create()->load($categoryId);

        // Get product collection filtered by category
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoryFilter($category);
        $collection->setPageSize(10);

        return $collection;
    }

    /**
     * Function to construct image URL from image path
     *
     * @param string $imagePath
     * @return string
     */
    public function getImageUrlFromPath($imagePath)
    {
        return $this->storeManager
            ->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $imagePath;
    }

    /**
     * Function to get Add to Cart URL
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartUrl($product)
    {
        return $this->getUrl('checkout/cart/add', ['product' =>
        $product->getId(), 'form_key' => $this->formKey->getFormKey()]);
    }

    /**
     * Get parameters for adding product to wishlist
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getAddToWishlistParams($product)
    {
        return $this->wishlistHelper->getAddParams($product);
    }

    /**
     * Get the form key for wishlist
     *
     * @return string
     */
    public function getFormKeyForWishlist()
    {
        $key = $this->formKey->getFormKey();
        return $key;
    }

    /**
     * Get array of details for associated simple products of a configurable product
     *
     * @param int $productId
     * @return array
     */
    public function getSimpleProductArray($productId)
    {
        // Step 2: Load Configurable Product
        $configurableProduct = $this->productRepository->getById($productId);

        // Step 3: Fetch Associated Simple Products
        $associatedProducts = $configurableProduct->getTypeInstance()->getUsedProducts($configurableProduct);

        $productDetails = [];

        foreach ($associatedProducts as $product) {
            $productId = $product->getId(); // Entity ID
            $productSku = $product->getSku(); // SKU
            $productPrice = $product->getPrice();
            $productColor = $product->getAttributeText('color'); // Price

            // Collect attributes into an array for each product
            $productDetails[] = [
                'entity_id' => $productId,
                'sku' => $productSku,
                'price' => $productPrice,
                'color' => $productColor
            ];
        }

        return $productDetails;
    }
}
