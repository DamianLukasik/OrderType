<?php
namespace Damian\OrderType\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\ObjectManager;
use Magento\Backend\Model\Session;

class SetOrderTypeId implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    protected $session;
    protected $order;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Sales\Model\Order $order,
        Session $session = null
    )
    {
        $this->_objectManager = $objectManager;
        $this->order = $order;
        $this->session = $session ?: ObjectManager::getInstance()->get(Session::class);
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {        
        $order = $observer->getEvent()->getOrder();
        if ($order==null) {
            $order = $this->order;
        }
        $orderId = $order->getId();
        $orderTypeId = $this->session->getData('order_type_id');        
        if ($orderId && $orderTypeId !== null) {
            $order->setData('order_type_id', $orderTypeId);
            $order->save();
            $objectmanager = ObjectManager::getInstance();
            $objectmanager->get('Psr\Log\LoggerInterface')->debug('Saved in the database in table sales_order: order_id='.$orderId.' column order_type_id='.$orderTypeId);
        }
        return $this;
    }
}