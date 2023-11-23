<?php
namespace Damian\OrderType\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use \Damian\OrderType\Model\ResourceModel\OrderType\CollectionFactory as OrderTypeCollectionFactory;
use \Magento\Sales\Model\OrderFactory;

class OrderType extends Column
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;
    /**
     * @var array
     */
    private $orderTypes;

    /**
     * OrderType constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param OrderTypeCollectionFactory $orderTypeCollectionFactory
     * @param OrderFactory $orderFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderTypeCollectionFactory $orderTypeCollectionFactory,
        OrderFactory $orderFactory,
        array $components = [],
        array $data = []
    ) {
        $this->_orderFactory = $orderFactory;
        $orderTypeCollection = $orderTypeCollectionFactory->create()->addFieldToSelect('*')->load()->getItems();
        $orderTypes = [];
        foreach ($orderTypeCollection as $orderType) {
            $orderTypes[$orderType->getId()] = $orderType->getTypeName();
        }
        $this->orderTypes = $orderTypes;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $orderTypes = $this->orderTypes;
            foreach ($dataSource['data']['items'] as & $item) {
                $order_type_id = $this->_orderFactory->create()->load($item['entity_id'])->getData('order_type_id');
                if (empty($order_type_id) || !array_key_exists($order_type_id, $orderTypes)) {
                    $item[$this->getData('name')] = __('-');
                } else {
                    $item[$this->getData('name')] = __($orderTypes[$order_type_id]);
                }                
            }
        }
        return $dataSource;
    }
}