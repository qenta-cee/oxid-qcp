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

/**
 * Payment class wrapper for WCP module
 *
 * @see oxPayment
 */
class wcp_payment extends wcp_payment_parent
{

    public function render()
    {
        $sReturn = parent::render();

        return $sReturn;
    }

    public static function isWcpPaymethod($paymethod)
    {
        return wdceepayment::isValidWCPPayment($paymethod);
    }

    /**
     * @return string
     */
    function getWcpRatePayConsumerDeviceId()
    {
        $config = oxRegistry::getConfig();

        if(isset($_SESSION['wcp-consumerDeviceId'])) {
            $consumerDeviceId = $_SESSION['wcp-consumerDeviceId'];
        } else {
            $timestamp = microtime();
            $consumerDeviceId = md5($config->getConfigParam('sWcpCustomerId') . "_" . $timestamp);
            $_SESSION['wcp-consumerDeviceId'] = $consumerDeviceId;
        }
        $ratepay = '<script language="JavaScript">var di = {t:"'.$consumerDeviceId.'",v:"WDWL",l:"Checkout"};</script>';
        $ratepay .= '<script type="text/javascript" src="//d.ratepay.com/'.$consumerDeviceId.'/di.js"></script>';
        $ratepay .= '<noscript><link rel="stylesheet" type="text/css" href="//d.ratepay.com/di.css?t='.$consumerDeviceId.'&v=WDWL&l=Checkout"></noscript>';
        $ratepay .= '<object type="application/x-shockwave-flash" data="//d.ratepay.com/WDWL/c.swf" width="0" height="0"><param name="movie" value="//d.ratepay.com/WDWL/c.swf" /><param name="flashvars" value="t='.$consumerDeviceId.'&v=WDWL"/><param name="AllowScriptAccess" value="always"/></object>';

        return $ratepay;
    }

    public function getWcpPaymentLogo($paymethod)
    {

        $conf = oxRegistry::getConfig();
        $modulePaths = $conf->getConfigParam('aModulePaths');
        $imgPath = $conf->getConfigParam('sShopURL') . '/modules/' . $modulePaths['wirecardcheckoutpage'] . '/out/img/';
        switch ($paymethod) {
            case 'wcp_bancontact_mistercash':
                return '<img src="' . $imgPath . 'bancontact_mistercash.png" />';
            case 'wcp_ccard':
                return '<img src="' . $imgPath . 'ccard.png" />';
            case 'wcp_ccard-moto':
                return '<img src="' . $imgPath . 'ccard.png" />';
            case 'wcp_ekonto':
                return '<img src="' . $imgPath . 'ekonto.png" />';
            case 'wcp_epay_bg':
                return '<img src="' . $imgPath . 'epay_bg.png" />';
            case 'wcp_eps':
                return '<img src="' . $imgPath . 'eps.png" />';
            case 'wcp_giropay':
                return '<img src="' . $imgPath . 'giropay.jpg" />';
            case 'wcp_idl':
                return '<img src="' . $imgPath . 'idl.png" />';
            case 'wcp_installment':
                return '<img src="' . $imgPath . 'installment.jpg" />';
            case 'wcp_invoice_b2b':
                return '<img src="' . $imgPath . 'invoice.png" />';
            case 'wcp_invoice_b2c':
                return '<img src="' . $imgPath . 'invoice.png" />';
            case 'wcp_maestro':
                return '<img src="' . $imgPath . 'maestro.png" />';
            case 'wcp_moneta':
                return '<img src="' . $imgPath . 'moneta.jpg" />';
            case 'wcp_masterpass':
                return '<img src="' . $imgPath . 'masterpass.png" />';
            case 'wcp_paypal':
                return '<img src="' . $imgPath . 'paypal.png" />';
            case 'wcp_pbx':
                return '<img src="' . $imgPath . 'pbx.jpg" />';
            case 'wcp_poli':
                return '<img src="' . $imgPath . 'poli.png" />';
            case 'wcp_przelewy24':
                return '<img src="' . $imgPath . 'przelewy24.jpg" />';
            case 'wcp_psc':
                return '<img src="' . $imgPath . 'psc.png" />';
            case 'wcp_sepa-dd':
                return '<img src="' . $imgPath . 'sepa.png" />';
            case 'wcp_skrillwallet':
                return '<img src="' . $imgPath . 'skrillwallet.jpg" />';
            case 'wcp_sofortueberweisung':
                return '<img src="' . $imgPath . 'sofort.png" />';
            case 'wcp_tatrapay':
                return '<img src="' . $imgPath . 'tatrapay.jpg" />';
            case 'wcp_trustly':
                return '<img src="' . $imgPath . 'trustly.jpg" />';
            case 'wcp_voucher':
                return '<img src="' . $imgPath . 'voucher.png" />';
            case 'wcp_trustpay':
                return '<img src="' . $imgPath . 'trustpay.jpg" />';
            default:
                return null;
        }
    }


    public function hasWcpDobField($sPaymentId)
    {
        if (in_array($sPaymentId, array('wcp_invoice_b2c', 'wcp_installment'))) {
            return true;
        }

        return false;
    }

    public function hasWcpVatIdField($sPaymentId)
    {

        if (oxRegistry::getConfig()->getConfigParam('sWcpInvoiceProvider') == 'PAYOLUTION') {
            if ($sPaymentId == 'wcp_invoice_b2b') {
                return true;
            }
        }

        return false;
    }

    public function validatePayment()
    {
        $parentResult = parent::validatePayment();
        $oConfig = oxRegistry::getConfig();
        $oSession = $this->getSession();
        $oUser = $this->getUser();
        $sPaymentId = (string )$oConfig->getRequestParameter('paymentid');
        $oLang = oxRegistry::get('oxLang');

        if (in_array($sPaymentId, array('wcp_idl', 'wcp_eps'))) {
            $oSession->setVariable('financialInstitution', oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_financialInstitution'));
        }

        if (in_array($sPaymentId,array('wcp_invoice_b2c','wcp_installment'))) {
            if ($this->hasWcpDobField($sPaymentId) && $oUser->oxuser__oxbirthdate == '0000-00-00') {
                $iBirthdayYear = oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_iBirthdayYear');
                $iBirthdayDay = oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_iBirthdayDay');
                $iBirthdayMonth = oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_iBirthdayMonth');

                if (empty($iBirthdayYear) || empty($iBirthdayDay) || empty($iBirthdayMonth)) {
                    $oSession->setVariable('wcp_payerrortext',
                        $oLang->translateString('WIRECARD_CHECKOUT_PAGE_PLEASE_FILL_IN_DOB',
                            $oLang->getBaseLanguage()));

                    return;
                }

                $dateData = array('day' => $iBirthdayDay, 'month' => $iBirthdayMonth, 'year' => $iBirthdayYear);
                $oSession->setVariable('wcp_dobData', $dateData);

                if (is_array($dateData)) {
                    $oUser->oxuser__oxbirthdate = new oxField($oUser->convertBirthday($dateData), oxField::T_RAW);
                    $oUser->save();
                }
            }

            //validate paymethod
            if (!$this->wcpValidateCustomerAge($oUser, 18)) {
                $oSession->setVariable('wcp_payerrortext',
                    sprintf($oLang->translateString('WIRECARD_CHECKOUT_PAGE_DOB_TOO_YOUNG',
                        $oLang->getBaseLanguage()), 18));

                return;
            }
        }
        if ('wcp_invoice_b2b' == $sPaymentId) {
            if ($oConfig->getConfigParam('sWcpInvoiceProvider') == 'PAYOLUTION') {
                $vatId = $oUser->oxuser__oxustid->value;
                if ($this->hasWcpVatIdField($sPaymentId) && empty($vatId)) {
                    $sVatId = oxRegistry::getConfig()->getRequestParameter('sVatId');

                    if (!empty($sVatId)) {
                        /** @var oxInputValidator $oInputValidator */
                        $oInputValidator = oxRegistry::get('oxInputValidator');
                        $aInput = array(
                            'oxuser__oxustid' => $sVatId,
                            'oxuser__oxcountryid' => $oUser->oxuser__oxcountryid->value,
                            'oxuser__oxcompany' => $oUser->oxuser__oxcompany->value
                        );
                        $oInputValidator->checkVatId($oUser, $aInput);

                        if ($oError = oxRegistry::get("oxInputValidator")->getFirstValidationError()) {
                            $oSession->setVariable('wcp_payerrortext', $oError->getMessage());

                            return;
                        }

                        $oUser->oxuser__oxustid = new oxField($sVatId, oxField::T_RAW);
                        $oUser->save();
                    }
                }
            }

        }

        if ($this->showWcpTrustedShopsCheckbox($sPaymentId)) {
            if (!oxRegistry::getConfig()->getRequestParameter('payolutionTerms')) {
                $oSession->setVariable('wcp_payerrortext',
                    $oLang->translateString('WIRECARD_CHECKOUT_PAGE_CONFIRM_PAYOLUTION_TERMS',
                        $oLang->getBaseLanguage()));

                $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                $oSmarty->assign("aErrors", array('payolutionTerms' => 1));

                return;
            }
        }


        return $parentResult;
    }


    /**
     * @return mixed
     */
    public function getWcpPaymentError()
    {
        $wcp_payment_error = '';

        if (oxRegistry::getSession()->hasVariable('wcp_payerrortext')) {
            $wcp_payment_error = oxRegistry::getSession()->getVariable('wcp_payerrortext');
            oxRegistry::getSession()->deleteVariable('wcp_payerrortext');
        }

        return $wcp_payment_error;
    }

    /**
     * @return bool
     */
    public function isWcpPaymentError()
    {
        if (oxRegistry::getSession()->hasVariable('wcp_payerrortext')) {
            return true;
        }

        return false;
    }


    /**
     * check if user is older than the given age
     * @param oxUser $oUser
     * @param integer $iMinAge
     * @return boolean
     */
    public function wcpValidateCustomerAge($oUser, $iMinAge = 18)
    {
        $dob = $oUser->oxuser__oxbirthdate->value;
        if ($dob && $dob != '0000-00-00') {
            $iAgeChecker = $iMinAge--;
            $dobObject = new DateTime($dob);
            $currentYear = date('Y');
            $currentMonth = date('m');
            $currentDay = date('d');
            $ageCheckDate = ($currentYear - $iAgeChecker) . '-' . $currentMonth . '-' . $currentDay;
            $ageCheckObject = new DateTime($ageCheckDate);
            if ($ageCheckObject < $dobObject) {
                //customer is younger than given age. PaymentType not available
                return false;
            }
        }

        return true;
    }

    public function wcpValidateAddresses($oUser, $oOrder)
    {
        //if delivery Address is not set it's the same as billing
        $oDelAddress = $oOrder->getDelAddressInfo();
        if ($oDelAddress) {
            if ($oDelAddress->oxaddress__oxcompany->value != $oUser->oxuser__oxcompany->value ||
                $oDelAddress->oxaddress__oxfname->value != $oUser->oxuser__oxfname->value ||
                $oDelAddress->oxaddress__oxlname->value != $oUser->oxuser__oxlname->value ||
                $oDelAddress->oxaddress__oxstreet->value != $oUser->oxuser__oxstreet->value ||
                $oDelAddress->oxaddress__oxstreetnr->value != $oUser->oxuser__oxstreetnr->value ||
                $oDelAddress->oxaddress__oxaddinfo->value != $oUser->oxuser__oxaddinfo->value ||
                $oDelAddress->oxaddress__oxcity->value != $oUser->oxuser__oxcity->value ||
                $oDelAddress->oxaddress__oxcountry->value != $oUser->oxuser__oxcountry->value ||
                $oDelAddress->oxaddress__oxstateid->value != $oUser->oxuser__oxstateid->value ||
                $oDelAddress->oxaddress__oxzip->value != $oUser->oxuser__oxzip->value ||
                $oDelAddress->oxaddress__oxfon->value != $oUser->oxuser__oxfon->value ||
                $oDelAddress->oxaddress__oxfax->value != $oUser->oxuser__oxfax->value ||
                $oDelAddress->oxaddress__oxsal->value != $oUser->oxuser__oxsal->value
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * check if basket currency is an allowed currency
     * @param oxBasket $oBasket
     * @param Array $aAllowedCurrencies
     * @return boolean
     */
    public function wcpValidateCurrency($oBasket, $aAllowedCurrencies = Array('EUR'))
    {
        $currency = $oBasket->getBasketCurrency();
        if (!in_array($currency->name, $aAllowedCurrencies)) {
            return false;
        }

        return true;
    }

    function showWcpTrustedShopsCheckbox($sPaymentId)
    {
        $installmentPayolution = oxRegistry::getConfig()->getConfigParam('sWcpInstallmentProvider') == 'PAYOLUTION';
        $invoicePayolution = oxRegistry::getConfig()->getConfigParam('sWcpInvoiceProvider') == 'PAYOLUTION';
        switch ($sPaymentId) {
            case 'wcp_installment':
                return $installmentPayolution ? oxRegistry::getConfig()->getConfigParam('bWcpInstallmentTrustedShopsCheckbox') : false;
            case 'wcp_invoice_b2b':
                return $invoicePayolution ? oxRegistry::getConfig()->getConfigParam('bWcpInvoiceb2bTrustedShopsCheckbox') : false;
            case 'wcp_invoice_b2c':
                return $invoicePayolution ? oxRegistry::getConfig()->getConfigParam('bWcpInvoiceb2cTrustedShopsCheckbox') : false;
            default:
                return false;
        }
    }

    function getWcpPayolutionTerms()
    {
        $oLang = oxRegistry::get('oxLang');
        $conf = oxRegistry::getConfig();

        return sprintf($oLang->translateString('WIRECARD_CHECKOUT_PAGE_PAYOLUTION_TERMS', $oLang->getBaseLanguage()),
            'https://payment.payolution.com/payolution-payment/infoport/dataprivacyconsent?mId=' . $conf->getConfigParam('sWcpPayolutionMId'));

    }

    /**
     * strips "WCP " prefix from paymethod description
     *
     * @param String paymethod description with prefix
     * @return String paymethod description without prefix
     **/
    public static function getWcpRawPaymentDesc($paymethodNameWithPrefix)
    {
        return str_replace('WCP ', '', $paymethodNameWithPrefix);
    }
}
