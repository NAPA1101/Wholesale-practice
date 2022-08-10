<?php

namespace Checkout\Wholesale\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Checkout\Wholesale\Helper\Data;

class PaymentMethodAvailable implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @param Session $checkoutSession
     * @param Data $helper
     */
    public function __construct(
        Session $checkoutSession,
        Data $helper
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        
    }

    public function execute(Observer $observer)
    {
        $groupId = $this->_checkoutSession->getQuote()->getCustomerGroupId();
        $payment_method_code = $observer->getEvent()->getMethodInstance()->getCode();

        if ($this->_helper->getGeneralConfig('enable') && $this->_helper->getLargeConfig('enable_large') && $groupId == 5){
            $sum = $this->_checkoutSession->getQuote()->getGrandTotal();
            if ($this->_helper->getLargeConfig('price') <= $sum) {
                $paymentMethod = $this->_helper->getPaymentLarge();
                if ($payment_method_code !== $paymentMethod){
                    $result = $observer->getEvent()->getResult();
                    $result->setData('is_available', false);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }

        if ($this->_helper->getGeneralConfig('enable') && $this->_helper->getWholesaleConfig('enable_wholesale') && $groupId == 4){
            $qty = $this->_checkoutSession->getQuote()->getItemsQty();
            if ($this->_helper->getWholesaleConfig('qty') <= $qty) {
                $paymentWholesale = $this->_helper->getPaymentWholesale();
                if ($payment_method_code !== $paymentWholesale) {
                    $result = $observer->getEvent()->getResult();
                    $result->setData('is_available', false);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}