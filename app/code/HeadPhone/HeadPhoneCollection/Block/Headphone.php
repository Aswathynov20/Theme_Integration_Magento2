<?php

namespace HeadPhone\HeadPhoneCollection\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Framework\Data\Form\FormKey;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class HeadPhone extends Template
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
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * HeadPhone constructor.
     * @param Template\Context $context
     * @param ProductCollectionFactory $productCollectionFactory
     * @param CategoryFactory $categoryFactory
     * @param WishlistHelper $wishlistHelper
     * @param FormKey $formKey
     * @param StoreManagerInterface $storeManager
     * @param ConfigurableType $configurableType
     * @param ProductRepositoryInterface $productRepository
     * @param PriceHelper $priceHelper
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
        PriceHelper $priceHelper,
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
        $this->priceHelper = $priceHelper;
    }

    /**
     * Get product collection filtered by category
     *
     * @return mixed
     */
    public function getProductCollection()
    {
        // Get category ID
        $categoryId = 103;

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
     * Convert product price to currency format
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     */
    public function convertProductPrice($product)
    {
        $productPrice = $product->getPrice();
        $convertedPrice = $this->priceHelper->currency($productPrice, true, false);
        return $convertedPrice;
    }

    /**
     * Construct image URL from image path
     *
     * @param string $imagePath
     * @return string
     */
    public function getImageUrlFromPath($imagePath)
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .
         'catalog/product' . $imagePath;
    }

    /**
     * Get Add to Cart URL
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartUrl($product)
    {
        return $this->getUrl('checkout/cart/add', ['product' => $product->getId(),
                'form_key' => $this->formKey->getFormKey()]);
    }

    /**
     * Get Add to Wishlist parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     */
    public function getAddToWishlistParams($product)
    {
        return $this->wishlistHelper->getAddParams($product);
    }

    /**
     * Get Form Key for Wishlist
     *
     * @return mixed
     */
    public function getFormKeyForWishlist()
    {
        $key = $this->formKey->getFormKey();
        return $key;
    }

    /**
     * Get array of simple product details
     *
     * @param int $productId
     * @return array
     */
    public function getSimpleProductArray($productId)
    {
        // Step 2: Load Configurable Product
        $configurableProduct = $this->productRepository->getById($productId);

        // Step 3: Fetch Associated Simple Products
        $associatedProducts = $configurableProduct->
          getTypeInstance()->getUsedProducts($configurableProduct);

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
                'price' => $this->convertProductPrice($product),
                'color' => $productColor
            ];
        }

        return $productDetails;
    }
}
