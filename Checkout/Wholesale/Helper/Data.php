<?php
namespace Checkout\Wholesale\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data
{
    protected $configWriter;

    const XML_PATH_CUSTOMPAY = 'wholesale/';
    const XML_PATH_PAYMENTLARGE = 'wholesale/large/payment';
    const XML_PATH_PAYMENTWHOLESALE = 'wholesale/wholesalegroup/payment_wholesale';
    const XML_PATH_SHIPPING = 'wholesale/wholesalegroup/shipping_wholesale';

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
   {
      $this->scopeConfig = $scopeConfig;
   }

    public function getConfigValue(string $field, int $storeId = null) : int
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getGeneralConfig(string $code, int $storeId = null) : int
    {
        return $this->getConfigValue(
            self::XML_PATH_CUSTOMPAY .'general/'. $code, $storeId
        );
    }

    public function getLargeConfig(string $code, int $storeId = null) : int
    {
        return $this->getConfigValue(
            self::XML_PATH_CUSTOMPAY .'large/'. $code, $storeId
        );
    }

    public function getWholesaleConfig(string $code, int $storeId = null) : int
    {
        return $this->getConfigValue(
            self::XML_PATH_CUSTOMPAY .'wholesalegroup/'. $code, $storeId
        );
    }

    public function getPaymentLarge() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_PAYMENTLARGE, $storeScope);
    }

    public function getPaymentWholesale() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_PAYMENTWHOLESALE, $storeScope);
    }

    public function getShippingWholesale() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_SHIPPING, $storeScope);
    }

}
