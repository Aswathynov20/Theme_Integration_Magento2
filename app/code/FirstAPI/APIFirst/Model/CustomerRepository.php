<?php
namespace FirstAPI\APIFirst\Model;

use FirstAPI\APIFirst\Api\CustomerTokenInterface;

class CustomerRepository implements CustomerTokenInterface
{
    /**
     * @var \Magento\Integration\Model\Oauth\TokenFactory
     */
    private $tokenModelFactory;

    /**
     * @param \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
     */
    public function __construct(
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
    ) {
        $this->tokenModelFactory = $tokenModelFactory;
    }

    /**
     * Get customer token by customer ID.
     *
     * @param int $customerId
     * @return string
     */
    public function getToken($customerId)
    {
        return $customerId;
    }
}
