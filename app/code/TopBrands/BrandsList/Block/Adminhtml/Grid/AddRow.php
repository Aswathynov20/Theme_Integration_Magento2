<?php

namespace TopBrands\BrandsList\Block\Adminhtml\Grid;

use TopBrands\BrandsList\Model\ResourceModel\Grid\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class AddRow extends \Magento\Backend\Block\Widget\Form\Container
{
 /**
  * Core registry instance.
  *
  * @var \Magento\Framework\Registry
  */
    protected $_coreRegistry = null;
    /**
     * @var CollectionFactory
     */
    protected $brandsCollection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * AddRow constructor.
     *
     * @param CollectionFactory $brandsCollection
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        CollectionFactory $brandsCollection,
        StoreManagerInterface $storeManager,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->brandsCollection = $brandsCollection;
        $this->storeManager = $storeManager;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize block.
     */
    protected function _construct()
    {
        $this->_objectId = 'row_id';
        $this->_blockGroup = 'TopBrands_BrandsList';
        $this->_controller = 'adminhtml_grid';
        parent::_construct();
        if ($this->_isAllowedAction('TopBrands_BrandsList::add_row')) {
            $this->buttonList->update('save', 'label', __('Save'));
        } else {
            $this->buttonList->remove('save');
        }
        $this->buttonList->remove('reset');
    }

    /**
     * Retrieve text for header element depending on loaded image.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Add Article Data');
    }

    /**
     * Check permission for passed action.
     *
     * @param string $resourceId
     *
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get form action URL.
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }

        return $this->getUrl('*/*/save');
    }

    /**
     * Get custom text.
     *
     * @return string
     */
    public function getText()
    {
        $test = "testing...";
        return $test;
    }

    /**
     * Get data for PHTML.
     *
     * @return array
     */
    public function getDataForPHTML()
    {
        $brands = $this->brandsCollection->create()->getItems();
        // var_dump($brands);
        return $brands;
    }

    /**
     * Get image URL from path.
     *
     * @param string $imagePath
     *
     * @return string
     */
    public function getImageUrlFromPath($imagePath)
    {
        return $this->storeManager->getStore()->
        getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $imagePath;
        // var_dump($imagePath);
    }
}
