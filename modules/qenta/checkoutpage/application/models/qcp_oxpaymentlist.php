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

class qcp_oxpaymentlist extends qcp_oxpaymentlist_parent
{

    public function getPaymentList($sShipSetId, $dPrice, $oUser = null)
    {
        $paymentList = parent::getPaymentList($sShipSetId, $dPrice, $oUser);

        if (array_key_exists('wcp_invoice_b2b', $paymentList) || array_key_exists('wcp_invoice_b2c',
                $paymentList) || array_key_exists('wcp_installment', $paymentList)
        ) {
            $dob = $oUser->oxuser__oxbirthdate->value;

            $oBasket = $this->getSession()->getBasket();
            $oOrder = oxNew('oxorder');

            if (array_key_exists('wcp_invoice_b2c', $paymentList)) {
                if (!$this->_isWCPInvoiceAvailable($oUser, $oBasket,
                        $oOrder) || !empty($oUser->oxuser__oxcompany->value)
                ) {
                    unset($paymentList['wcp_invoice_b2c']);
                } elseif ($dob && $dob == '0000-00-00') {
                    $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                    $oSmarty->assign("bShowDobField", true);

                    $dobData = oxRegistry::getSession()->getVariable('wcp_dobData');
                    if (!empty($dobData)) {
                        $oSmarty->assign("dobData", oxRegistry::getSession()->getVariable('wcp_dobData'));
                    }
                }
            }

            if (array_key_exists('wcp_invoice_b2b', $paymentList)) {
                $vatId = $oUser->oxuser__oxustid->value;

                if (!$this->_isWCPInvoiceAvailable($oUser, $oBasket,
                        $oOrder) || empty($oUser->oxuser__oxcompany->value)
                ) {
                    unset($paymentList['wcp_invoice_b2b']);
                }
                if (oxRegistry::getConfig()->getConfigParam('sQcpInvoiceProvider') == 'PAYOLUTION') {
                    $sVatId = oxRegistry::getSession()->getVariable('wcp_vatId');
                    if (empty($vatId)) {
                        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                        $oSmarty->assign("sVatId", $sVatId);
                        $oSmarty->assign("bShowVatIdField", true);
                    }
                }
            }

            if (array_key_exists('wcp_installment', $paymentList)) {
                if (!$this->_isWCPInstallmentAvailable($oUser, $oBasket, $oOrder)) {
                    unset($paymentList['wcp_installment']);
                } elseif ($dob && $dob == '0000-00-00') {
                    $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                    $oSmarty->assign("bShowDobField", true);

                    $dobData = oxRegistry::getSession()->getVariable('wcp_dobData');
                    if (!empty($dobData)) {
                        $oSmarty->assign("dobData", oxRegistry::getSession()->getVariable('wcp_dobData'));
                    }
                }
            }
        }
        if (array_key_exists('wcp_ccard-moto', $paymentList)) {
            if (!$this->getUser()->inGroup('oxidadmin')) {
                unset($paymentList['wcp_ccard-moto']);
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
    protected function _isWCPInvoiceAvailable($oUser, $oBasket, $oOrder)
    {
        if (!($oUser || $oBasket || $oOrder)) {
            return false;
        }

        $oPayment = oxNew("qcp_payment");

        if (!$oPayment->wcpValidateCustomerAge($oUser)) {
            return false;
        }
        if (!(oxRegistry::getConfig()->getConfigParam('bQcpPayolutionAllowDifferingAddresses') && oxRegistry::getConfig()->getConfigParam('sInvoiceInstallmentProvider') == 'PAYOLUTION') && !$oPayment->wcpValidateAddresses($oUser,
                $oOrder)
        ) {
            return false;
        }
        if (!$oPayment->wcpValidateCurrency($oBasket)) {
            return false;
        }

        return true;
    }

    /**
     * check if paymentType installment is available
     * @param oxUser $oUser
     * @return boolean
     */
    protected function _isWCPInstallmentAvailable($oUser, $oBasket, $oOrder)
    {
        if (!($oUser || $oBasket || $oOrder)) {
            return false;
        }

        $oPayment = oxNew("qcp_payment");

        if (!$oPayment->wcpValidateCustomerAge($oUser)) {
            return false;
        }
        if (!(oxRegistry::getConfig()->getConfigParam('bQcpPayolutionAllowDifferingAddresses') && oxRegistry::getConfig()->getConfigParam('sInvoiceInstallmentProvider') == 'PAYOLUTION') && !$oPayment->wcpValidateAddresses($oUser,
                $oOrder)
        ) {
            return false;
        }
        if (!$oPayment->wcpValidateCurrency($oBasket)) {
            return false;
        }

        return true;
    }

}