<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/plugins/#legalNotice
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

class qcp_oxpaymentlist extends qcp_oxpaymentlist_parent
{

    public function getPaymentList($sShipSetId, $dPrice, $oUser = null)
    {
        $paymentList = parent::getPaymentList($sShipSetId, $dPrice, $oUser);

        if (array_key_exists('qcp_invoice_b2b', $paymentList) || array_key_exists('qcp_invoice_b2c',
                $paymentList) || array_key_exists('qcp_installment', $paymentList)
        ) {
            $dob = $oUser->oxuser__oxbirthdate->value;

            $oBasket = $this->getSession()->getBasket();
            $oOrder = oxNew('oxorder');

            if (array_key_exists('qcp_invoice_b2c', $paymentList)) {
                if (!$this->_isQCPInvoiceAvailable($oUser, $oBasket,
                        $oOrder) || !empty($oUser->oxuser__oxcompany->value)
                ) {
                    unset($paymentList['qcp_invoice_b2c']);
                } elseif ($dob && $dob == '0000-00-00') {
                    $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                    $oSmarty->assign("bShowDobField", true);

                    $dobData = oxRegistry::getSession()->getVariable('qcp_dobData');
                    if (!empty($dobData)) {
                        $oSmarty->assign("dobData", oxRegistry::getSession()->getVariable('qcp_dobData'));
                    }
                }
            }

            if (array_key_exists('qcp_invoice_b2b', $paymentList)) {
                $vatId = $oUser->oxuser__oxustid->value;

                if (!$this->_isQCPInvoiceAvailable($oUser, $oBasket,
                        $oOrder) || empty($oUser->oxuser__oxcompany->value)
                ) {
                    unset($paymentList['qcp_invoice_b2b']);
                }
                if (oxRegistry::getConfig()->getConfigParam('sQcpInvoiceProvider') == 'PAYOLUTION') {
                    $sVatId = oxRegistry::getSession()->getVariable('qcp_vatId');
                    if (empty($vatId)) {
                        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                        $oSmarty->assign("sVatId", $sVatId);
                        $oSmarty->assign("bShowVatIdField", true);
                    }
                }
            }

            if (array_key_exists('qcp_installment', $paymentList)) {
                if (!$this->_isQCPInstallmentAvailable($oUser, $oBasket, $oOrder)) {
                    unset($paymentList['qcp_installment']);
                } elseif ($dob && $dob == '0000-00-00') {
                    $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                    $oSmarty->assign("bShowDobField", true);

                    $dobData = oxRegistry::getSession()->getVariable('qcp_dobData');
                    if (!empty($dobData)) {
                        $oSmarty->assign("dobData", oxRegistry::getSession()->getVariable('qcp_dobData'));
                    }
                }
            }
        }
        if (array_key_exists('qcp_ccard-moto', $paymentList)) {
            if (!$this->getUser()->inGroup('oxidadmin')) {
                unset($paymentList['qcp_ccard-moto']);
            }
        }

        $this->_aArray = $paymentList;

        return $this->_aArray;
    }

    /**
     * check if paymentType invoice is available
     * @param oxUser $oUser
     * @return boolean
     */
    protected function _isQCPInvoiceAvailable($oUser, $oBasket, $oOrder)
    {
        if (!($oUser || $oBasket || $oOrder)) {
            return false;
        }

        $oPayment = oxNew("qcp_payment");

        if (!$oPayment->qcpValidateCustomerAge($oUser)) {
            return false;
        }
        if (!(oxRegistry::getConfig()->getConfigParam('bQcpPayolutionAllowDifferingAddresses') && oxRegistry::getConfig()->getConfigParam('sInvoiceInstallmentProvider') == 'PAYOLUTION') && !$oPayment->qcpValidateAddresses($oUser,
                $oOrder)
        ) {
            return false;
        }
        if (!$oPayment->qcpValidateCurrency($oBasket)) {
            return false;
        }

        return true;
    }

    /**
     * check if paymentType installment is available
     * @param oxUser $oUser
     * @return boolean
     */
    protected function _isQCPInstallmentAvailable($oUser, $oBasket, $oOrder)
    {
        if (!($oUser || $oBasket || $oOrder)) {
            return false;
        }

        $oPayment = oxNew("qcp_payment");

        if (!$oPayment->qcpValidateCustomerAge($oUser)) {
            return false;
        }
        if (!(oxRegistry::getConfig()->getConfigParam('bQcpPayolutionAllowDifferingAddresses') && oxRegistry::getConfig()->getConfigParam('sInvoiceInstallmentProvider') == 'PAYOLUTION') && !$oPayment->qcpValidateAddresses($oUser,
                $oOrder)
        ) {
            return false;
        }
        if (!$oPayment->qcpValidateCurrency($oBasket)) {
            return false;
        }

        return true;
    }

}