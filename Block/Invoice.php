<?php

namespace LCB\DompdfDocuments\Block;

class Invoice extends \Magento\Framework\View\Element\Template {

    /**
     * @var Address\Renderer
     */
    protected $_addressRenderer;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface 
     */
    protected $_scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Sales\Model\Order\Address\Renderer $addressRenderer
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer, 
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList, 
        array $data = []
    )
    {
        $this->_addressRenderer = $addressRenderer;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * Returns string with formatted address
     *
     * @param Address $address
     * @return null|string
     */
    public function getFormattedAddress($address)
    {
        return $this->_addressRenderer->format($address, 'pdf');
    }

    /**
     * Returns string with formatted price and currency
     *
     * @param Price $price
     * @return null|string
     */
    public function getFormattedPrice($price)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $formattedPrice = $priceHelper->currency($price, true, false);
        return $formattedPrice;
    }

    /**
     * Returns array with store general info
     *
     * @param null
     * @return null|array
     */
    public function getStoreInfo()
    {
        return $storeInfo[] = $this->_scopeConfig->getValue('general/store_information');
    }

    /**
     * Returns absolute path to desirable directory
     *
     * @param string
     * @return null|string
     */
    public function getAbsolutePath($dir)
    {
        return $this->_directoryList->getPath($dir);
    }

    /**
     * Get invoice language
     * 
     * @return string
     */
    public function getLocaleCode()
    {
        $invoice = $this->getInvoice();
        return $this->_scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $invoice->getStoreId());
    }

}
