<?php

namespace TopBrands\BrandsList\Model;

use TopBrands\BrandsList\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * Cache tag for the model
     */
    public const CACHE_TAG = 'topbrands_grid';

    /**
     * Cache tag for the model
     *
     * @var string
     */
    protected $_cacheTag = 'topbrands_grid';

    /**
     * Event prefix for the model
     *
     * @var string
     */
    protected $_eventPrefix = 'topbrands_grid';

    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init(\TopBrands\BrandsList\Model\ResourceModel\Grid::class);
    }

    /**
     * Get Article ID
     *
     * @return int|null
     */
    public function getArticleId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Set Article ID
     *
     * @param int $articleId
     * @return $this
     */
    public function setArticleId($articleId)
    {
        return $this->setData(self::ID, $articleId);
    }

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    // Uncomment the following if needed
    // /**
    //  * Get Email
    //  *
    //  * @return string|null
    //  */
    // public function getEmail()
    // {
    //     return $this->getData(self::EMAIL);
    // }

    // /**
    //  * Set Email
    //  *
    //  * @param string $email
    //  * @return $this
    //  */
    // public function setEmail($email)
    // {
    //     return $this->setData(self::EMAIL, $email);
    // }
}
