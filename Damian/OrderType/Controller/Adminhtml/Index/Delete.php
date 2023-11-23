<?php

namespace Damian\OrderType\Controller\Adminhtml\Index;

use Damian\OrderType\Controller\Adminhtml\Index\Index;

class Delete extends Index
{
    public function execute()
    {
        parent::execute();
        $resultRedirect = $this->resultRedirect;
        try {
            $orderType = $this->orderType;
            $orderType->load($this->id);
            $orderType->delete();
            $this->messageManager->addSuccessMessage(__('The order type has been removed'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Damian_OrderType::delete');
    }
}