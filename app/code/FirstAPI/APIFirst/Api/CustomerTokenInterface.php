<?php
namespace FirstAPI\APIFirst\Api;

interface CustomerTokenInterface
{
    /**
     * @param int $customerId
     * @return string
     */
    public function getToken($customerId);
}
