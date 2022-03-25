<?php

/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/plugins/#legalNotice
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
 */

/**
 * Payment class wrapper for QCP module
 *
 * @see oxPayment
 */
class qcp_payment extends qcp_payment_parent
{

    public function render()
    {
        $sReturn = parent::render();

        setcookie("sid", $_COOKIE['sid'], time() + 180, "/" . '; samesite=' . "None; Secure", $_SESSION['host'], true, false);

        return $sReturn;
    }

    public static function isQcpPaymethod($paymethod)
    {
        return qentapayment::isValidQCPPayment($paymethod);
    }

    /**
     * @return string
     */
    function getQcpRatePayConsumerDeviceId()
    {
        $config = oxRegistry::getConfig();

        if (isset($_SESSION['qcp-consumerDeviceId'])) {
            $consumerDeviceId = $_SESSION['qcp-consumerDeviceId'];
        } else {
            $timestamp = microtime();
            $consumerDeviceId = md5($config->getConfigParam('sQcpCustomerId') . "_" . $timestamp);
            $_SESSION['qcp-consumerDeviceId'] = $consumerDeviceId;
        }

        if ($config->getConfigParam('sQcpInvoiceProvider') == "RATEPAY" || $config->getConfigParam('sQcpInstallmentProvider') == "RATEPAY") {
            $ratepay = '<script language="JavaScript">var di = {t:"' . $consumerDeviceId . '",v:"WDWL",l:"Checkout"};</script>';
            $ratepay .= '<script type="text/javascript" src="//d.ratepay.com/' . $consumerDeviceId . '/di.js"></script>';
            $ratepay .= '<noscript><link rel="stylesheet" type="text/css" href="//d.ratepay.com/di.css?t=' . $consumerDeviceId . '&v=WDWL&l=Checkout"></noscript>';
            $ratepay .= '<object type="application/x-shockwave-flash" data="//d.ratepay.com/WDWL/c.swf" width="0" height="0"><param name="movie" value="//d.ratepay.com/WDWL/c.swf" /><param name="flashvars" value="t=' . $consumerDeviceId . '&v=WDWL"/><param name="AllowScriptAccess" value="always"/></object>';

            return $ratepay;
        }
    }

    public function getQcpPaymentLogo($paymethod)
    {

        $conf = oxRegistry::getConfig();
        $modulePaths = $conf->getConfigParam('aModulePaths');
        $imgPath = $conf->getConfigParam('sShopURL') . '/modules/' . $modulePaths['qentacheckoutpage'] . '/out/img/';
        switch ($paymethod) {
            case 'qcp_bancontact_mistercash':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'bancontact_mistercash.png" />';
            case 'qcp_ccard':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'ccard.png" />';
            case 'qcp_ccard-moto':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'ccard.png" />';
            case 'qcp_ekonto':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'ekonto.png" />';
            case 'qcp_epay_bg':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'epay_bg.png" />';
            case 'qcp_eps':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'eps.png" />';
            case 'qcp_giropay':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'giropay.jpg" />';
            case 'qcp_idl':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'idl.png" />';
            case 'qcp_installment':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'installment.jpg" />';
            case 'qcp_invoice_b2b':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'invoice.png" />';
            case 'qcp_invoice_b2c':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'invoice.png" />';
            case 'qcp_maestro':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'maestro.png" />';
            case 'qcp_moneta':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'moneta.jpg" />';
            case 'qcp_masterpass':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'masterpass.png" />';
            case 'qcp_paypal':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'paypal.png" />';
            case 'qcp_pbx':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'pbx.jpg" />';
            case 'qcp_poli':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'poli.png" />';
            case 'qcp_przelewy24':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'przelewy24.jpg" />';
            case 'qcp_psc':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'psc.png" />';
            case 'qcp_sepa-dd':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'sepa.png" />';
            case 'qcp_skrillwallet':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'skrillwallet.jpg" />';
            case 'qcp_sofortueberweisung':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'sofort.png" />';
            case 'qcp_tatrapay':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'tatrapay.jpg" />';
            case 'qcp_trustly':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'trustly.jpg" />';
            case 'qcp_voucher':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'voucher.png" />';
            case 'qcp_trustpay':
                return '<img style="margin: 0 8px; width: 1.5rem;" src="' . $imgPath . 'trustpay.jpg" />';
            default:
                return null;
        }
    }


    public function hasQcpDobField($sPaymentId)
    {
        if (in_array($sPaymentId, array('qcp_invoice_b2c', 'qcp_installment'))) {
            return true;
        }

        return false;
    }

    public function hasQcpVatIdField($sPaymentId)
    {

        if (oxRegistry::getConfig()->getConfigParam('sQcpInvoiceProvider') == 'PAYOLUTION') {
            if ($sPaymentId == 'qcp_invoice_b2b') {
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
        $sPaymentId = (string)$oConfig->getRequestParameter('paymentid');
        $oLang = oxRegistry::get('oxLang');

        if (in_array($sPaymentId, array('qcp_idl', 'qcp_eps'))) {
            $oSession->setVariable('financialInstitution', oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_financialInstitution'));
        }

        if (in_array($sPaymentId, array('qcp_invoice_b2c', 'qcp_installment'))) {
            if ($this->hasQcpDobField($sPaymentId) && $oUser->oxuser__oxbirthdate == '0000-00-00') {
                $iBirthdayYear = oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_iBirthdayYear');
                $iBirthdayDay = oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_iBirthdayDay');
                $iBirthdayMonth = oxRegistry::getConfig()->getRequestParameter($sPaymentId . '_iBirthdayMonth');

                if (empty($iBirthdayYear) || empty($iBirthdayDay) || empty($iBirthdayMonth)) {
                    $oSession->setVariable(
                        'qcp_payerrortext',
                        $oLang->translateString(
                            'QENTA_CHECKOUT_PAGE_PLEASE_FILL_IN_DOB',
                            $oLang->getBaseLanguage()
                        )
                    );

                    return;
                }

                $dateData = array('day' => $iBirthdayDay, 'month' => $iBirthdayMonth, 'year' => $iBirthdayYear);
                $oSession->setVariable('qcp_dobData', $dateData);

                if (is_array($dateData)) {
                    $oUser->oxuser__oxbirthdate = new oxField($oUser->convertBirthday($dateData), oxField::T_RAW);
                    $oUser->save();
                }
            }

            //validate paymethod
            if (!$this->qcpValidateCustomerAge($oUser, 18)) {
                $oSession->setVariable(
                    'qcp_payerrortext',
                    sprintf($oLang->translateString(
                        'QENTA_CHECKOUT_PAGE_DOB_TOO_YOUNG',
                        $oLang->getBaseLanguage()
                    ), 18)
                );

                return;
            }
        }
        if ('qcp_invoice_b2b' == $sPaymentId) {
            if ($oConfig->getConfigParam('sQcpInvoiceProvider') == 'PAYOLUTION') {
                $vatId = $oUser->oxuser__oxustid->value;
                if ($this->hasQcpVatIdField($sPaymentId) && empty($vatId)) {
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
                            $oSession->setVariable('qcp_payerrortext', $oError->getMessage());

                            return;
                        }

                        $oUser->oxuser__oxustid = new oxField($sVatId, oxField::T_RAW);
                        $oUser->save();
                    }
                }
            }
        }

        if ($this->showQcpTrustedShopsCheckbox($sPaymentId)) {
            if (!oxRegistry::getConfig()->getRequestParameter('payolutionTerms')) {
                $oSession->setVariable(
                    'qcp_payerrortext',
                    $oLang->translateString(
                        'QENTA_CHECKOUT_PAGE_CONFIRM_PAYOLUTION_TERMS',
                        $oLang->getBaseLanguage()
                    )
                );

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
    public function getQcpPaymentError()
    {
        $qcp_payment_error = '';

        if (oxRegistry::getSession()->hasVariable('qcp_payerrortext')) {
            $qcp_payment_error = oxRegistry::getSession()->getVariable('qcp_payerrortext');
            oxRegistry::getSession()->deleteVariable('qcp_payerrortext');
        }

        return $qcp_payment_error;
    }

    /**
     * @return bool
     */
    public function isQcpPaymentError()
    {
        if (oxRegistry::getSession()->hasVariable('qcp_payerrortext')) {
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
    public function qcpValidateCustomerAge($oUser, $iMinAge = 18)
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

    public function qcpValidateAddresses($oUser, $oOrder)
    {
        //if delivery Address is not set it's the same as billing
        $oDelAddress = $oOrder->getDelAddressInfo();
        if ($oDelAddress) {
            if (
                $oDelAddress->oxaddress__oxcompany->value != $oUser->oxuser__oxcompany->value ||
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
    public function qcpValidateCurrency($oBasket, $aAllowedCurrencies = array('EUR'))
    {
        $currency = $oBasket->getBasketCurrency();
        if (!in_array($currency->name, $aAllowedCurrencies)) {
            return false;
        }

        return true;
    }

    function showQcpTrustedShopsCheckbox($sPaymentId)
    {
        $installmentPayolution = oxRegistry::getConfig()->getConfigParam('sQcpInstallmentProvider') == 'PAYOLUTION';
        $invoicePayolution = oxRegistry::getConfig()->getConfigParam('sQcpInvoiceProvider') == 'PAYOLUTION';
        switch ($sPaymentId) {
            case 'qcp_installment':
                return $installmentPayolution ? oxRegistry::getConfig()->getConfigParam('bQcpInstallmentTrustedShopsCheckbox') : false;
            case 'qcp_invoice_b2b':
                return $invoicePayolution ? oxRegistry::getConfig()->getConfigParam('bQcpInvoiceb2bTrustedShopsCheckbox') : false;
            case 'qcp_invoice_b2c':
                return $invoicePayolution ? oxRegistry::getConfig()->getConfigParam('bQcpInvoiceb2cTrustedShopsCheckbox') : false;
            default:
                return false;
        }
    }

    function getQcpPayolutionTerms()
    {
        $oLang = oxRegistry::get('oxLang');
        $conf = oxRegistry::getConfig();

        return sprintf(
            $oLang->translateString('QENTA_CHECKOUT_PAGE_PAYOLUTION_TERMS', $oLang->getBaseLanguage()),
            'https://payment.payolution.com/payolution-payment/infoport/dataprivacyconsent?mId=' . $conf->getConfigParam('sQcpPayolutionMId')
        );
    }

    /**
     * strips "QCP " prefix from paymethod description
     *
     * @param String paymethod description with prefix
     * @return String paymethod description without prefix
     **/
    public static function getQcpRawPaymentDesc($paymethodNameWithPrefix)
    {
        return str_replace('QCP ', '', $paymethodNameWithPrefix);
    }
}
