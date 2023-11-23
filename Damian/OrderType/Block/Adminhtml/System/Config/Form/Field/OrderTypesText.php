<?php
namespace Damian\OrderType\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template\Context;
use \Damian\OrderType\Model\ResourceModel\OrderType\CollectionFactory as OrderTypeCollectionFactory;

class OrderTypesText extends Field
{    
    /**
     * CollectionFactory
     * @var null|CollectionFactory
     */
    protected $_orderTypeCollectionFactory;

    /**
     * OrderTypesText constructor
     * 
     * @param Context $context
     * @param OrderTypeCollectionFactory $orderTypeCollectionFactory
     * @param array $data
     * @return void
     */
    public function __construct(
        Context $context,
        OrderTypeCollectionFactory $orderTypeCollectionFactory,
        array $data = []
    ) {
        $this->_orderTypeCollectionFactory = $orderTypeCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Render HTML list of order types
     * 
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $orderTypeCollection = $this->_orderTypeCollectionFactory->create();
        $orderTypeCollection->addFieldToSelect('*')->load();
        $orderTypes = $orderTypeCollection->getItems();
        $fields = '';
        foreach ($orderTypes as $orderType) {
            $id = $orderType->getId();
            $typename = $orderType->getTypeName();
            $buttons = $this->renderButton($id,'edit').$this->renderButton($id,'delete');
            $fields .= '<tr id="'.$id.'_tr_ordertype"><td></td><td class="value">'.$this->renderField($id, $typename, $buttons, true).'<td></td></tr>';
        }
        $addNewOrderType = '<tr id="0_tr_ordertype"><td></td><td class="value">'.$this->renderField(0, '', $this->renderButton(0, 'add')).'<td></td></tr>';
        return '<table cellspacing="0" class="form-list"><tbody>'.$fields.$addNewOrderType.'</tbody></table>'.$this->addScriptJS();
    }

    /**
     * Render HTML element of order type
     * 
     * @param int|null $id
     * @param string|null $typename
     * @param string|null $buttons
     * @param bool|null $disabled
     * @return string
     */
    public function renderField($id, $typename, $buttons, $disabled=false)
    {
        return '<input '.($disabled ? 'disabled' : '').' id="'.$id.'_ordertype" value="'.$typename.'" class="required-entry validate-zero-or-greater validate-digits input-text admin__control-text" type="text"></td><td>'.$buttons.'</td>';
    }

    /**
     * Render HTML button with onclick
     * 
     * @param int|null $id
     * @param string|null $text
     * @return string
     */
    public function renderButton($id, $text)
    {
        return '<button class="ordertypebutton" id="'.$text.$id.'_ordertype" type="button" onclick="'.strtolower($text).'OrderTypes('.$id.')">'.ucfirst($text).'</button>';
    }

    /**
     * Render Javascript script
     * 
     * @return string
     */
    public function addScriptJS() {
        return "<script>
            function editOrderTypes(id) { 
                if (jQuery('#'+id+'_ordertype').prop('disabled')) {
                    jQuery('#'+id+'_ordertype').prop('disabled', false);
                    jQuery('#edit'+id+'_ordertype').html('Save');
                } else {
                    jQuery('#'+id+'_ordertype').prop('disabled', true);
                    jQuery('#edit'+id+'_ordertype').html('Edit');
                    var val = jQuery('#'+id+'_ordertype').val();
                    ajaxActionOrderTypes(id, val, 'edit');
                }
            }
        
            function deleteOrderTypes(id) {
                jQuery('#'+id+'_tr_ordertype').hide();
                ajaxActionOrderTypes(id, '', 'delete');
            }
        
            function addOrderTypes(id) {
                var val = jQuery('#0_ordertype').val();
                if(val==''){return -1;}
                i = -1;
                el = null;
                do {
                    i++;
                    el = jQuery('#'+i+'_ordertype').val();
                } while (el);
                ajaxActionOrderTypes(id, val, 'add');
            }

            function ajaxActionOrderTypes(id, val, action) {
                jQuery('.ordertypebutton').prop('disabled', true);
                var baseUrl = '/admin/damian_ordertypesmanager_action/index/'+action;
                var formKey = jQuery('input[name=\"form_key\"]').val();
                jQuery.ajax({
                    url: baseUrl,
                    type: 'POST',
                    data: {
                        id: id,
                        value: val,
                        form_key: formKey
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            console.log('Success');
                            console.log(response);
                        } else {
                            console.error(response);
                        }
                    },
                    error: function () {
                        console.error(response);
                    }
                });
                setTimeout(function(){history.go(0);}, 650);
            }
        </script>";
    }
}