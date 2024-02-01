<?php

namespace AddMenu\AddCategoryMenu\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\CategoryFactory;

class Addcategorymenu extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * CategoryMenu constructor.
     *
     * @param Template\Context $context
     * @param CollectionFactory $categoryCollectionFactory
     * @param CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $categoryCollectionFactory,
        CategoryFactory $categoryFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Get category menu.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function getCategoryMenu()
    {
        // Create an array with category names
        $categoryNames = ['Home','Men','Women','Offer Zone', 'Appliance', 'Body Care','Electronics',];
        // var_dump($categoryNames);
        // Create or load each category
        foreach ($categoryNames as $categoryName) {
            $category = $this->categoryFactory->create();
            $category->loadByAttribute('name', $categoryName);
            $existingCategory = $this->categoryFactory->create()->getCollection()
                ->addAttributeToFilter('name', $categoryName)
                ->getFirstItem();
            if (!$existingCategory->getId()) {
                // Category doesn't exist, create it
                $parentCategory = $this->categoryFactory->create()->load(2);
                $category->setName($categoryName)
                    ->setIsActive(1)
                    ->setUrlKey($this->generateUrlKey($categoryName))
                    ->setParentId($parentCategory->getId())
                    ->setPath($parentCategory->getPath())
                    ->setLevel($parentCategory->getLevel() + 1)
                    ->setSortOrder(1)
                    ->save();
            }
        }

        // Fetch all categories including the newly created or existing categories
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addAttributeToSelect('*');

        return $categoryCollection;
    }

        /**
         * Custom function to generate URL key from category name.
         *
         * @param string $name
         * @return string
         */
    private function generateUrlKey($name)
    {
        return strtolower(str_replace(' ', '-', $name . 'test'));
    }
}
