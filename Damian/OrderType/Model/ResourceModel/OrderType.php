<?php

namespace Damian\OrderType\Model\ResourceModel;

class OrderType extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('damian_ordertype_order_type', 'order_type_id');
    }
}