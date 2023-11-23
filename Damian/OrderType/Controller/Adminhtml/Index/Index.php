<?php

namespace Damian\OrderType\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;

class Index extends Action
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var OrderType
     */
    protected $orderType;
    protected $resultRedirect;
    protected $id;
    protected $val;

    public function __construct(
        Context $context,
        RequestInterface $request,
        array $data = []
    ) {
        $this->request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
        $isValidFormKey = $this->_objectManager->get(\Magento\Framework\Data\Form\FormKey\Validator::class)->validate($this->request);
        $this->resultRedirect = $this->resultRedirectFactory->create();
        $this->id = (int)$this->getRequest()->getPost('id');
        $this->val = $this->getRequest()->getPost('value');
        try {
            if (!$isValidFormKey) {
                throw new \Exception(__('Invalid Form Key. Please refresh the page.'));
            }
            $this->orderType = $this->_objectManager->create(\Damian\OrderType\Model\OrderType::class);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Damian_OrderType::index');
    }
}