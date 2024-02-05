<?php

namespace TopBrands\BrandsList\Model\ResourceModel\Grid;

use TopBrands\BrandsList\Model\Grid;
use TopBrands\BrandsList\Model\ResourceModel\Grid as GridResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(Grid::class, GridResourceModel::class);
    }
}
