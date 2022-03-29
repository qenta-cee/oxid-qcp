<?php

/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/plugins/#legalNotice
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
 */

class qcp_order extends qcp_order_parent
{

    public function init()
    {
        header('Set-Cookie: ' . 'sid' . '=' . $_COOKIE['sid'], time() + 180, "/" . '; SameSite=None; Secure');

        return parent::init();
    }

    /**
     * Hookpoint. check if it's an qcp Order.
     * @param $iSuccess - the order success state.
     * @return String - checkoutUrl or parent return.
     * @see order
     */
    protected function _getNextStep($iSuccess)
    {
        $oPayment = $this->getPayment();

        if ($oPayment && qentapayment::isValidQCPPayment($oPayment->oxpayments__oxid->value)) {
            if (is_numeric($iSuccess) && ($iSuccess == oxOrder::ORDER_STATE_ORDEREXISTS || $iSuccess == oxOrder::ORDER_STATE_OK)) {
                $oSession = $this->getSession();
                $oSession->setVariable('qcpBasket', serialize($oSession->getBasket()));
                $oSession->delBasket();
                $oPayment = $this->getPayment();
                $oOrder = $this->_getOrder();

                /** @var qcp_OrderDbGateway $oDbOrder */
                $oDbOrder = oxNew('qcp_OrderDbGateway');
                $aOrderData = array(
                    'BASKET' => $oSession->getVariable('qcpBasket'),
                    'OXORDERID' => $oOrder->getId()
                );
                $oDbOrder->insert($aOrderData);

                $checkoutType = qentapayment::getCheckoutType(str_replace(
                    'qcp_',
                    '',
                    $oPayment->oxpayments__oxid->value
                ));

                if ($checkoutType == 'IFRAME') {
                    return 'qentapayment?fnc=checkoutIFrame';
                } else {
                    return 'qentapayment?fnc=checkoutForm';
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


    public function isQcpPaymethod($sPaymentID)
    {
        return qcp_payment::isQcpPaymethod($sPaymentID);
    }

    public function getQcpRawPaymentDesc($paymethodNameWithPrefix)
    {
        return qcp_payment::getQcpRawPaymentDesc($paymethodNameWithPrefix);
    }
}
