<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/plugins/#legalNotice
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

class qcp_oxorder extends qcp_oxorder_parent
{

    public function qcpCheckOrderExists()
    {
        $sOxId = $this->_sOXID;

        return parent::_checkOrderExist($sOxId);
    }

    /**
     * don't send order email before payment has finished
     *
     * @param null $oUser
     * @param null $oBasket
     * @param null $oPayment
     *
     * @return bool|int
     */
    protected function _sendOrderByEmail($oUser = null, $oBasket = null, $oPayment = null)
    {
        if (qentapayment::isValidQCPPayment($this->oxorder__oxpaymenttype->value)) {
            return self::ORDER_STATE_OK;
        }

        return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
    }

    // will be send by confirm
    public function sendQentaCheckoutPageOrderByEmail($oBasket, $oUserPayment = null)
    {
        $sUserId = $this->oxorder__oxuserid;
        /** @var oxUser $oUser */
        $oUser = oxNew('oxUser');
        $oUser->load($sUserId);
        $this->_setUser($oUser);

        /** @var oxUserPayment $oUserPayment */
        if ($oUserPayment === null) {
            $oUserPayment = $this->_setPayment($oBasket->getPaymentId());
        }

        return parent::_sendOrderByEmail($oUser, $oBasket, $oUserPayment);
    }
}