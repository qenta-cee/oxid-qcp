<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/shop_plugins:info
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

class qcp_order extends qcp_order_parent
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

                /** @var qcp_OrderDbGateway $oDbOrder */
                $oDbOrder = oxNew('qcp_OrderDbGateway');
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
        return qcp_payment::isWcpPaymethod($sPaymentID);
    }

    public function getWcpRawPaymentDesc($paymethodNameWithPrefix)
    {
        return qcp_payment::getWcpRawPaymentDesc($paymethodNameWithPrefix);
    }
}