<?php

namespace Damian\OrderType\Model\ResourceModel\OrderType;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Remittance File Collection Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            '\Damian\OrderType\Model\OrderType',
            '\Damian\OrderType\Model\ResourceModel\OrderType'
        );
    }
}