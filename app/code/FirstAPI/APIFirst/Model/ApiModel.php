<?php
namespace FirstAPI\APIFirst\Model;

class ApiModel
{
    /**
     * @var \Magento\Integration\Model\Oauth\TokenFactory
     */
    private $tokenModelFactory;
    /**
     * @param \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
     */
    public function __construct(
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory)
    {
        $this->tokenModelFactory = $tokenModelFactory;
    }
    /**
     * @inheritdoc
     */
    public function getcustomerId($customerId)
    {
        return $customerId;

    }

}