<?php

namespace Damian\OrderType\Controller\Adminhtml\Index;

use Damian\OrderType\Controller\Adminhtml\Index\Index;

class Add extends Index
{
    public function execute()
    {
        parent::execute();
        $resultRedirect = $this->resultRedirect;
        try {
            $orderType = $this->orderType;
            $orderType->setData('id', $this->id);
            $orderType->setData('type_name', $this->val);
            $orderType->save();
            $this->messageManager->addSuccessMessage(__('The order type has been added'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Damian_OrderType::add');
    }
}