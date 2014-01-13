<?php
 
class Tiramizoo_Shipping_Block_Adminhtml_Catalog_Product_Tab_Action 
    extends Mage_Adminhtml_Block_Template 
    implements Mage_Adminhtml_Block_Widget_Tab_Interface 
{
    public function _construct()
    {
        parent::_construct();
         
        $productId  = (int) $this->getRequest()->getParam('id');
        $product    = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));
        $product->load($productId);

        $this->setProduct($product);
        $this->setTemplate('tiramizoo/catalog/product/tab/action.phtml');
    }
     
    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Tiramizoo Options');
    }
     
    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view Tiramizoo configuration');
    }
     
    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return Mage::helper('tiramizoo_shipping/data')->isActive();
    }
     
    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
 
}