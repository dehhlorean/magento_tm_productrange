<?php

class TM_Productrange_IndexController extends Mage_Core_Controller_Front_Action  {

  public function indexAction()
  {
    $this->loadLayout();

    // set account link to active
    $layout = $this->getLayout();
    $navigationBlock = $layout->getBlock('customer_account_navigation');
    if ($navigationBlock) {
        $navigationBlock->setActive('productrange/index/index');
    }

    $this->renderLayout();
  }


  /**
   * Only let logged in users access my account pages
   */
  public function preDispatch()
  {
    parent::preDispatch();
    $action = $this->getRequest()->getActionName();
    $loginUrl = Mage::helper('customer')->getLoginUrl();

    if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
        $this->setFlag('', self::FLAG_NO_DISPATCH, true);
    }
  }

  /**
   * Get product collection and return results JSON
   */
  public function getproductsAction()
  {
    $hi = $this->getRequest()->getParam('hi');
    $lo = $this->getRequest()->getParam('lo');
    $sortBy = $this->getRequest()->getParam('sortBy');

    if ((!empty($hi) || !empty($lo) || !empty($sortBy)) && (is_numeric($hi) && is_numeric($lo))) {

      $productModel = Mage::getModel('catalog/product');
      $productCollection = $productModel->getCollection()
          ->setPageSize(10)
          ->setOrder('price', $sortBy)
          ->addAttributeToSelect('*')
          ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
          ->addFieldToFilter('price', array('from' => $lo, 'to' => $hi))
          ->load();

      $jsonProducts = array();
      foreach ($productCollection as $product) {
          $product->getData();
          $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
          $jsonProducts[] = array(
                      'name' => ''.$product->getName().'',
                      'url' => ''.$product->getProductUrl().'',
                      'price' => ''.$product->getFormatedPrice().'',
                      'sku' => ''.$product->getSku().'',
                      'qty' => ''.$stock->getQty().'',
                      'thumbnail' => ''.$product->getThumbnail().''
          );
      }

      $data = json_encode($jsonProducts);
      echo $data;
    }
  }

}
