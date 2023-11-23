<?php
namespace Damian\OrderType\Api\Data;

interface OrderTypeInterface
{
    const ORDER_TYPE_ID = 'order_type_id';
    const TYPE_NAME = 'type_name';

    /**
     * Get Order Type Id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Type name
     *
     * @return string|null
     */
    public function getTypeName();
}