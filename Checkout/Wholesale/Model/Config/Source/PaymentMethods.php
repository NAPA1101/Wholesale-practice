<?php

namespace Checkout\Wholesale\Model\Config\Source;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Payment\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;

class PaymentMethods extends AbstractModel
{
    /**
     * Payment Model Config
     *
     * @var Config
     */
    protected $_paymentConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $_appConfigScopeConfigInterface;
    
    /**
     * @param Registry $registry
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param Config $paymentConfig
     * @param ScopeConfigInterface $scopeConfigInterface
     */

    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Config $paymentConfig,
        ScopeConfigInterface $scopeConfigInterface,
        array $data = []
    ) {
        $this->_paymentConfig = $paymentConfig;
        $this->_appConfigScopeConfigInterface = $scopeConfigInterface;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    
    /**
     * Get active/enabled payment methods
     * 
     * @return array
     */ 
    public function getActivePaymentMethods() 
    {
        $payments = $this->_paymentConfig->getActiveMethods();
        $methods = array();
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->_appConfigScopeConfigInterface
                ->getValue('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode
            );
        }
        return $methods;
    }

    public function toOptionArray(): array
    {
        return $this->getActivePaymentMethods();
    }
}
