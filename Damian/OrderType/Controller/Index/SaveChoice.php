<?php
namespace Damian\OrderType\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\Session;
use Magento\Framework\App\ObjectManager;

class SaveChoice extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
     /**
     * @var Session
     */
    protected $session;

    /**
     * SaveChoice constructor
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Session $session = null
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $session ?: ObjectManager::getInstance()->get(Session::class);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $orderTypeId = (int) $this->getRequest()->getParam('order_type_id');
        try {
            $this->session->setData('order_type_id', $orderTypeId);
            $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
            $objectmanager->get('Psr\Log\LoggerInterface')->debug('Save to session: orderTypeId = '.$orderTypeId);
            $result->setData(['success' => true]);
        } catch (\Exception $e) {
            $result->setData(['success' => false, 'error' => $e->getMessage()]);
        }
        return $result;
    }
}