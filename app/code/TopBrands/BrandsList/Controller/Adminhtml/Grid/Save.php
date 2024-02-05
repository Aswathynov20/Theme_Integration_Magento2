<?php

namespace TopBrands\BrandsList\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Store\Model\StoreManagerInterface;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;
use Magento\Framework\Filesystem;

class Save extends Action
{
    /**
     * @var \TopBrands\BrandsList\Model\GridFactory
     */
    protected $gridFactory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \TopBrands\BrandsList\Model\GridFactory $gridFactory
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \TopBrands\BrandsList\Model\GridFactory $gridFactory,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
    }

    /**
     * Execute action
     *
     * @return Redirect
     */
    public function execute()
    {
        $baseurl = $this->storeManager->getStore()->getBaseUrl();

        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            return $this->_redirect('topbrands/grid/addrow');
        }

        try {
            $rowData = $this->gridFactory->create();

            // Handle image upload
            if ($this->getRequest()->getFiles('image')['name'] != '') {
                $uploader = $this->_objectManager->create(FileUploader::class, ['fileId' => 'image']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                $mediaDirectory = $this->filesystem->
                    getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

                $result = $uploader->save($mediaDirectory->getAbsolutePath('Grid/images'));

                $data['image'] = $baseurl . 'media/Grid/images/' . $result['file'];
            }

            $rowData->setData($data);

            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }

            $rowData->save();
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        return $this->_redirect('topbrands/grid/index');
    }

    /**
     * Check permission
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Topbands_Grid::save');
    }
}
