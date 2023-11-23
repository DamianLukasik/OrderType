<?php

namespace Damian\OrderType\Model;

use \Magento\Framework\Model\AbstractModel;
use \Damian\OrderType\Api\Data\OrderTypeInterface;

/**
 * Class File
 * @package Damian\OrderType\Model
 */
class OrderType extends AbstractModel implements OrderTypeInterface
{
    /**
     * OrderType Initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Damian\OrderType\Model\ResourceModel\OrderType');
    }

    /**
     * Get Order Type Id
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ORDER_TYPE_ID);
    }

    /**
     * Get Type name
     *
     * @return string|null
     */
    public function getTypeName()
    {
        return $this->getData(self::TYPE_NAME);
    }
}