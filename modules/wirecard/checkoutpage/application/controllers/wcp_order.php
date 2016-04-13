<?php
/**
 * Shop System Plugins - Terms of Use
 *
 * The plugins offered are provided free of charge by Wirecard Central Eastern Europe GmbH
 * (abbreviated to Wirecard CEE) and are explicitly not part of the Wirecard CEE range of
 * products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 2 (GPLv2) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Wirecard CEE does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the plugins at their own risk. Wirecard CEE does not guarantee their full
 * functionality neither does Wirecard CEE assume liability for any disadvantages related to
 * the use of the plugins. Additionally, Wirecard CEE does not guarantee the full functionality
 * for customized shop systems or installed plugins of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the plugin's functionality before starting productive
 * operation.
 *
 * By installing the plugin into the shop system the customer agrees to these terms of use.
 * Please do not use the plugin if you do not agree to these terms of use!
 */

class wcp_order extends wcp_order_parent
{

    public function init()
    {
        return parent::init();
    }

    /**
     * Hookpoint. check if it's an wcp Order.
     * @param $iSuccess - the order success state.
     * @return String - checkoutUrl or parent return.
     * @see order
     */
    protected function _getNextStep($iSuccess)
    {
        $oPayment = $this->getPayment();

        if ($oPayment && wdceepayment::isValidWCPPayment($oPayment->oxpayments__oxid->value)) {
            if (is_numeric($iSuccess) && ($iSuccess == oxOrder::ORDER_STATE_ORDEREXISTS || $iSuccess == oxOrder::ORDER_STATE_OK)) {
                $oSession = $this->getSession();
                $oSession->setVariable('wcpBasket', serialize($oSession->getBasket()));
                $oSession->delBasket();
                $oPayment = $this->getPayment();
                $oOrder = $this->_getOrder();

                /** @var wcp_OrderDbGateway $oDbOrder */
                $oDbOrder = oxNew('wcp_OrderDbGateway');
                $aOrderData = Array(
                    'BASKET' => $oSession->getVariable('wcpBasket'),
                    'OXORDERID' => $oOrder->getId()
                );
                $oDbOrder->insert($aOrderData);

                $checkoutType = wdceepayment::getCheckoutType(str_replace('wcp_', '',
                    $oPayment->oxpayments__oxid->value));

                if ($checkoutType == 'IFRAME') {
                    return 'wdceepayment?fnc=checkoutIFrame';
                } else {
                    return 'wdceepayment?fnc=checkoutForm';
                }
            }
        }

        return parent::_getNextStep($iSuccess);
    }

    /**
     * Returns current order object
     *
     * @return oxOrder
     */
    protected function _getOrder()
    {
        /** @var oxOrder $oOrder */
        $oOrder = oxNew("oxOrder");
        $bSuccess = $oOrder->load(oxRegistry::getSession()->getVariable('sess_challenge'));

        return $bSuccess ? $oOrder : null;
    }


    public function isWcpPaymethod($sPaymentID)
    {
        return wcp_payment::isWcpPaymethod($sPaymentID);
    }

    public function getWcpRawPaymentDesc($paymethodNameWithPrefix)
    {
        return wcp_payment::getWcpRawPaymentDesc($paymethodNameWithPrefix);
    }
}