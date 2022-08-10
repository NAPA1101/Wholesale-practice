<?php
namespace Checkout\Wholesale\Model\Config\Source;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Config;

class ShippingMethods extends AbstractModel
{

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Config
     */
    protected $_shippingConfig;
    
    /**
     * @param Registry $registry
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param ScopeConfigInterface $scopeConfigInterface
     * @param Config $shippingConfig
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        ScopeConfigInterface $scopeConfigInterface,
        Config $shippingConfig,
        array $data = []
    ) {

        $this->_scopeConfig = $scopeConfigInterface;
        $this->_shippingConfig = $shippingConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getActiveShippingMethod() 
    {
        $shippings = $this->_shippingConfig->getActiveCarriers();
        $methods = array();
        foreach($shippings as $shippingCode => $shippingModel) {
            if($carrierMethods = $shippingModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $method) {
                    $carrierTitle = $this->_scopeConfig->getValue('carriers/'. $shippingCode.'/title');
                    $methods[] = array('value'=>$shippingCode,'label'=>$carrierTitle);
                }
            } else {
                return null;
            }
        }
        return $methods;
    }

    public function toOptionArray(): array
    {
        return $this->getActiveShippingMethod();
    }
}
