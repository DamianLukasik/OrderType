<?php
namespace Damian\OrderType\Block\Cart;

use Magento\Framework\View\Element\Template;
use \Damian\OrderType\Model\ResourceModel\OrderType\CollectionFactory as OrderTypeCollectionFactory;

class OrderType extends Template
{
    /**
     * CollectionFactory
     * @var null|CollectionFactory
     */
    protected $_orderTypeCollectionFactory = null;
    
    /**
     * OrderType constructor
     *
     * @param Context $context
     * @param OrderTypeCollectionFactory $orderTypeCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        OrderTypeCollectionFactory $orderTypeCollectionFactory,
        array $data = []
    ) {
        $this->_orderTypeCollectionFactory = $orderTypeCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get Items of order types
     *
     * @return mixed
     */
    public function getOrderTypes()
    {
        $orderTypeCollection = $this->_orderTypeCollectionFactory->create();
        $orderTypeCollection->addFieldToSelect('*')->load();
        return $orderTypeCollection->getItems();
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'Order Type';
    }
}