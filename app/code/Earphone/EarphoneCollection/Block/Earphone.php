<?php

namespace Earphone\EarphoneCollection\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Framework\Data\Form\FormKey;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;

class Earphone extends Template
{
    protected $productCollectionFactory;
    protected $categoryFactory;
    protected $wishlistHelper;
    protected $formKey;
    protected $storeManager;
    protected $configurableType;
    protected $productRepository;

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

    public function getProductCollection()
    {
        // Get category ID
        $categoryId = 93;
         var_dump($categoryId);dd();

        // Get category model
        $category = $this->categoryFactory->create()->load($categoryId);

        // Get product collection filtered by category
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoryFilter($category);
        $collection->setPageSize(10);
        return $collection;
    }

    // Function to construct image URL from image path
    public function getImageUrlFromPath($imagePath)
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $imagePath;
    }

    // Function to get Add to Cart URL
    public function getAddToCartUrl($product)
    {
        return $this->getUrl('checkout/cart/add', ['product' => $product->getId(), 'form_key' => $this->formKey->getFormKey()]);
    }

    public function getAddToWishlistParams($product)
    {
        return $this->wishlistHelper->getAddParams($product);
    }

    public function getFormKeyForWishlist()
    {
        return $this->formKey;
    }
    public function getRegularPrice($product)
    {
        $priceInfo = $product->getPriceInfo();
        // var_dump($priceInfo);dd();
        return $priceInfo->getPrice('regular_price')->getAmount()->getValue();
    }

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
            $productColor = $product->getAttributeText('color');
    
            // Use regular price instead of special price
            $productRegularPrice = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
            // var_dump($productRegularPrice);dd();
            // Collect attributes into an array for each product
            $productDetails[] = [
                'entity_id' => $productId,
                'sku' => $productSku,
                'price' => $productPrice,
                'regular_price' => $productRegularPrice,
                'color' => $productColor
            
            ];
        }
        // var_dump($productDetails);dd();
        return $productDetails;
    }
    
}
