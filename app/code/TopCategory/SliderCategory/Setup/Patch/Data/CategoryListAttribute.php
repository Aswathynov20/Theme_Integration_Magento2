<?php
namespace TopCategory\SliderCategory\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Widget\Model\Widget\InstanceFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Cms\Block\Widget\Block;

class CategoryListAttribute implements DataPatchInterface, PatchVersionInterface
{
    /**

     * @var ModuleDataSetupInterface

     */
    private $moduleDataSetup;
    /**
     * @var InstanceFactory
     */
    private $widgetInstanceFactory;
    /**
     * @var Reader
     */
    private $moduleReader;

    /**

     * @var BlockFactory

     */
    private $blockFactory;
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;
    /**
     * @var File
     */
    private $file;

    /**
     * ProductSliderWidget constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param File $file
     * @param Reader $moduleReader
     * @param BlockFactory $blockFactory
     * @param InstanceFactory $widgetInstanceFactory
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        File $file,
        Reader $moduleReader,
        BlockFactory $blockFactory,
        InstanceFactory $widgetInstanceFactory,
        \Magento\Framework\App\State $state
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->widgetInstanceFactory = $widgetInstanceFactory;
        $this->state = $state;
        $this->file = $file;
        $this->moduleReader = $moduleReader;
    }

    /**
     * Apply function
     *
     * @return DataPatchInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function apply()
    {
        // Set Area code to prevent the Exception during setup:upgrade
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $this->moduleDataSetup->startSetup();
        $cmsBlockData = [
            'title' => 'Top new Categories slider',
            'identifier' => 'Top new Categories Slider Block',
            'content' => '{{block class="Magento\Framework\View\Element\Template"
            template="TopCategory_SliderCategory::category.phtml"}}',
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 30
        ];
        $cmsBlock = $this->blockFactory->create()->setData($cmsBlockData)->save();
        $widgetData = [
            'instance_type' => Block::class,
            'instance_code' => 'cms_static_block',
            'theme_id' => '7',
            'title' => 'Top new  Categories Slider',
            'store_ids' => '1',
            'widget_parameters' => '{"block_id":"'.$cmsBlock->getId().'"}',
            'sort_order' => 0,
            'page_groups' => [[
                'page_id' => 1,
                'page_group' => 'pages',
                'layout_handle' => 'default',
                'for' => 'all',
                'pages' => [
                    'page_id' => null,
                    'layout_handle' => 'cms_index_index',
                    'block' => 'content',
                    'for' => 'all',
                    'template' => 'widget/static_block/default.phtml'
                ]
            ]]
        ];
        $widget = $this->widgetInstanceFactory->create();
        $widget->setData($widgetData)->save();
        $this->moduleDataSetup->endSetup();
    }

    /**
     * Get the Dependencies
     *
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get the version
     *
     * @return string
     */
    public static function getVersion()
    {
        return '2.1.1';
    }

    /**
     * Get the aliases
     *
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }
}
