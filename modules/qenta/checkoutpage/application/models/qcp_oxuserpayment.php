<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/shop_plugins/info/
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

class qcp_oxuserpayment extends qcp_oxuserpayment_parent
{
    public function isQcpPaymethod($sPaymentID)
    {
        return qcp_payment::isQcpPaymethod($sPaymentID);
    }

    public function getQcpRawPaymentDesc($paymethodNameWithPrefix)
    {
        return qcp_payment::getQcpRawPaymentDesc($paymethodNameWithPrefix);
    }
}