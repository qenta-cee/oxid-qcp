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
 * viewscript to render payment-redirect
 * if order-view would have been used there would be some problems with
 * low-stock items
 */
class wdceepayment extends oxUBase
{
    protected $_oOrder = null;

    protected $_oBasket = null;

    protected static $_PAYMENT_WIRECARD_CHECKOUT_URL = 'api.qenta.com';
    protected static $_PAYMENT_INIT_URL = 'https://api.qenta.com/page/init-server.php';

    protected static $_PLUGIN_VERSION = '3.0.0';

    protected static $_CUSTOMER_ID_DEMO_MODE = 'D200001';
    protected static $_CUSTOMER_ID_TEST_MODE = 'D200411';
    protected static $_SECRET_DEMO_MODE = 'B8AKTPWBRMNBV455FG6M2DANE99WU2';
    protected static $_SECRET_TEST_MODE = 'CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ';
    protected static $_SHOP_ID_DEMO_MODE = '';
    protected static $_SHOP_ID_TEST_MODE = '';

    protected static $_VALID_PAYMENT_TYPES = Array(
        'CCARD',
        'CCARD-MOTO',
        'MAESTRO',
        'EPS',
        'IDL',
        'GIROPAY',
        'TATRAPAY',
        'TRUSTPAY',
        'SOFORTUEBERWEISUNG',
        'SKRILLWALLET',
        'MASTERPASS',
        'BANCONTACT_MISTERCASH',
        'PRZELEWY24',
        'MONETA',
        'POLI',
        'EKONTO',
        'TRUSTLY',
        'PBX',
        'PSC',
        'PAYPAL',
        'EPAY_BG',
        'SEPA-DD',
        'INVOICE_B2B',
        'INVOICE_B2C',
        'INSTALLMENT',
        'VOUCHER',
        'SELECT',
    );

    protected static $_PAYMENT_CANCELED_ERRORCODE = 7;
    protected static $_PAYMENT_FAILED_ERRORCODE = 8;
    protected static $_LOG_FILE_NAME = 'qentaCheckoutPageConfirm.log';
    protected static $_CUSTOMER_STATEMENT_LENGTH = 23;

    public function init()
    {
        if (!oxRegistry::getSession()->getVariable('sess_challenge')) {
            if (isset($_POST['sess_challenge'])) {
                oxRegistry::getSession()->setVariable('sess_challenge', $_POST['sess_challenge']);
            } else {
                oxRegistry::getSession()->setVariable('sess_challenge', '');
            }
        }

        $oOrder = $this->_getOrder();
        // not a valid order or wcp payment. redirect to payment selection.
        if (!$oOrder || !self::isValidWCPPayment($oOrder->oxorder__oxpaymenttype->value)) {
            self::paymentRedirect();
        }

        parent::init();
    }

    /**
     * Writes a log-line to confirm-logfile.
     *
     * @param $text log
     *            text
     */
    protected function _wcpConfirmLogging($text)
    {
        $date = date("Y-m-d H:i:s");

        if ($this->getConfig()->getConfigParam('bQcpLogConfirmations') == 1) {
            oxRegistry::getUtils()->writeToLog($date . ': ' . $text . "\n", self::$_LOG_FILE_NAME);
        }
    }

    protected function _getOrder()
    {
        if ($this->_oOrder === null) {
            $oOrder = oxNew('oxorder');
            $oOrder->load(oxRegistry::getSession()->getVariable('sess_challenge'));
            $this->_oOrder = $oOrder;
        }

        return $this->_oOrder;
    }

    protected function _getBasket()
    {
        if ($this->_oBasket === null) {
            $this->_oBasket = false;
            if ($oBasket = $this->getSession()->getBasket()) {
                $this->_oBasket = $oBasket;
            }
        }

        return $this->_oBasket;
    }

    public function checkoutIFrame()
    {
        $oConfig = $this->getConfig();
        $this->addGlobalParams();
        $this->_aViewData['oxcmp_basket'] = $this->_getBasket();
        $this->_aViewData['qcpIFrameUrl'] = html_entity_decode($oConfig->getShopCurrentUrl()) . 'cl=wdceepayment&fnc=checkoutForm';
        $this->_sThisTemplate = 'page/checkout/qcp_checkout_iframe.tpl';
    }

    /**
     * generating autosubmiting checkout form.
     */
    public function checkoutForm()
    {
        $oOrder = $this->_getOrder();
        $oConfig = $this->getConfig();

        $oOrder = $this->_setOrderFolder($oOrder, $oConfig->getConfigParam('sQcpCheckoutFolder'));
        $oOrder->save();

        $request = $this->_createWCPRequestArray($oOrder);

        $initResult = $this->_initPaymentRequest($request);

        if ($initResult['result'] == 'OK') {
            $this->_aViewData['wcpRequest'] = $request;
            $this->_aViewData['qcpPaymentUrl'] = $initResult['redirectUrl'];
            $this->_sThisTemplate = 'page/checkout/qcp_checkout_page.tpl';
        } else {
            $oSession = $this->getSession();
            $failureFolder = $this->getConfig()->getConfigParam('sQcpFailureFolder');
            $oOrder->cancelOrder();
            $oOrder = $this->_setOrderFolder($oOrder, $failureFolder);
            $oOrder->save();

            $oSession->setBasket(unserialize($oSession->getVariable('wcpBasket')));
            // force oxid to use a new Order for next try.
            $oSession->deleteVariable('sess_challenge');
            // clean up wcpPaymentState
            $oSession->deleteVariable('wcpPaymentState');
            // redirect to payment page with correct error.

            $oSession->setVariable('wcp_payerrortext', $initResult['message']);
            if ($request['checkoutType'] == 'IFRAME') {
                $this->_sThisTemplate = 'page/checkout/qcp_return_iframe.tpl';

                $sNextStep = oxRegistry::getConfig()->getShopCurrentUrl() . 'cl=payment';
                $sNextStep .= '&lang=' . (int)oxRegistry::getLang()->getBaseLanguage();
                $sNextStep .= '&payerror=' . self::$_PAYMENT_FAILED_ERRORCODE;

                $this->_aViewData['qcpIFrameUrl'] = $sNextStep;
            } else {
                self::paymentRedirect('FAILURE', oxRegistry::getLang()->getBaseLanguage());
            }
        }
    }

    private function _initPaymentRequest($request)
    {
        //perform init request and get redirect URL
        $content = http_build_query($request);
        $header = "Host: " . self::$_PAYMENT_WIRECARD_CHECKOUT_URL . "\r\n"
            . "User-Agent: " . $_SERVER["HTTP_USER_AGENT"] . "\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n"
            . "Content-Length: " . strlen($content) . "\r\n"
            . "Connection: close\r\n";

        $options = array(
            'http' => array(
                'header' => $header,
                'method' => 'POST',
                'content' => $content,
            )
        );

        $context = stream_context_create($options);

        if (!$result = file_get_contents(self::$_PAYMENT_INIT_URL, false, $context)) {
            $oLang = oxRegistry::get('oxLang');
            $response["message"] = $oLang->translateString('WIRECARD_CHECKOUT_PAGE_COMMUNICATION_ERROR',
                $oLang->getBaseLanguage());
        } else {
            parse_str($result, $response);
        }

        //redirect user
        if (isset($response["redirectUrl"])) {
            return array('result' => 'OK', 'redirectUrl' => $response["redirectUrl"]);
        } else {
            return array('result' => 'ERROR', 'message' => $response["message"]);
        }
    }

    private function getSecret() {
        $oConfig = $this->getConfig();
        switch ($oConfig->getConfigParam('sQcpPluginMode')) {
            case 'Demo':
                return self::$_SECRET_DEMO_MODE;
            case 'Test':
                return self::$_SECRET_TEST_MODE;
            case 'Live':
            default:
                return trim($oConfig->getConfigParam('sQcpSecret'));
        }
    }

    private function getCustomerId() {
        $oConfig = $this->getConfig();
        switch ($oConfig->getConfigParam('sQcpPluginMode')) {
            case 'Demo':
                return self::$_CUSTOMER_ID_DEMO_MODE;
            case 'Test':
                return self::$_CUSTOMER_ID_TEST_MODE;
            case 'Live':
            default:
                return trim($oConfig->getConfigParam('sQcpCustomerId'));
        }
    }

    private function getShopId() {
        $oConfig = $this->getConfig();
        switch ($oConfig->getConfigParam('sQcpPluginMode')) {
            case 'Demo':
                return self::$_SHOP_ID_DEMO_MODE;
            case 'Test':
                return self::$_SHOP_ID_TEST_MODE;
            case 'Live':
            default:
                return trim($oConfig->getConfigParam('sQcpShopId'));
        }
    }

    /**
     * creates an array used for WCP Checkout.
     *
     * @param $oOrder -
     *            the oxOrder Object
     * @return String[] - Array with all Requestparameters
     */
    protected function _createWCPRequestArray($oOrder)
    {
        $oConfig = $this->getConfig();
        $returnUrl = html_entity_decode($oConfig->getShopCurrentUrl());
        $sUrl = html_entity_decode($oConfig->getShopCurrentUrl()) . 'action=confirm';

        $confirmUrl = $sUrl;
        $shopName = 'Oxid ' . $oConfig->getEdition();
        $shopVersion = $oConfig->getVersion() . ' ' . $oConfig->getRevision();
        $pluginName = 'WirecardCEE_WCP';
        $pluginVersion = self::$_PLUGIN_VERSION;
        $versionString = base64_encode($shopName . '; ' . $shopVersion . '; mobile detect ' . WirecardCEE_MobileDetect::VERSION . '; ' . $pluginName . '; ' . $pluginVersion);
        $paymenttypeShop = strtoupper(str_replace('wcp_', '', $oOrder->oxorder__oxpaymenttype->value));
        $paymenttype = $paymenttypeShop;

        //change invoice and installment paymenttypes
        switch ($paymenttypeShop) {
            case 'INVOICE_B2B':
            case 'INVOICE_B2C':
                $paymenttype = 'INVOICE';
                break;
            case 'MAESTRO':
                $paymenttype = 'CCARD';
                break;
        }

        $orderReference = str_pad($oOrder->oxorder__oxordernr->value, '10', '0', STR_PAD_LEFT);
        $customerStatementString = $oConfig->getConfigParam('sQcpShopName') . ' id:' . $orderReference;
        $customerStatementLength = ($paymenttype != 'POLI') ? self::$_CUSTOMER_STATEMENT_LENGTH : 9;

        if ($paymenttype == 'POLI') {
            $customerStatementString = substr($oConfig->getConfigParam('sQcpShopName'), 0, 9);
        } elseif (strlen($orderReference) > $customerStatementLength) {
            $customerStatementString = substr($orderReference, -$customerStatementLength);
        } elseif (strlen($customerStatementString) > $customerStatementLength) {
            $customerStatementString = substr($oConfig->getConfigParam('sQcpShopName'), 0,
                    $customerStatementLength - 14) . ' id:' . $orderReference;
        }

        $request = Array();


        oxRegistry::getUtils()->writeToLog(__METHOD__. ': init payment with ' . $paymenttype . "\n", self::$_LOG_FILE_NAME);

        // WCP RequestParameters
        $request['customerId'] = $this->getCustomerId();
        $request['language'] = oxRegistry::getLang()->getLanguageAbbr();
        $request['paymenttype'] = $paymenttype;
        $request['shop_paymenttype'] = $paymenttypeShop;
        $request['amount'] = $oOrder->getTotalOrderSum();
        $request['currency'] = $oConfig->getActShopCurrencyObject()->name;
        $request['orderDescription'] = $oOrder->oxorder__oxordernr->value;
        $request['successUrl'] = $returnUrl;
        $request['cancelUrl'] = $returnUrl;
        $request['failureUrl'] = $returnUrl;
        $request['serviceUrl'] = $oConfig->getConfigParam('sQcpServiceUrl');

        $request['confirmUrl'] = $confirmUrl;
        $request['consumerIpAddress'] = $_SERVER['REMOTE_ADDR'];
        $request['consumerUserAgent'] = $_SERVER['HTTP_USER_AGENT'];

        $request['pendingUrl'] = $returnUrl;
        $request['duplicateRequestCheck'] = ($oConfig->getConfigParam('bWcpDuplicateRequestCheck') == 1) ? 'yes' : 'no';

        $request['orderReference'] = "aa".$orderReference;
        $request['customerStatement'] = $customerStatementString;

        $request['displayText'] = $oConfig->getConfigParam('sQcpDisplayText');
        $request['imageUrl'] = $oConfig->getConfigParam('sQcpImageUrl');

        $request['autoDeposit'] = ($oConfig->getConfigParam('bWcpAutoDeposit') == 1) ? 'yes' : 'no';
        $request['confirmMail'] = $oConfig->getConfigParam('bWcpConfirmMail');
        $request['maxRetries'] = $oConfig->getConfigParam('sQcpMaxRetries');
        $request['paymenttypeSortOrder'] = $oConfig->getConfigParam('sQcpPaymentTypeSortOrder');

        $request['shopId'] = $this->getShopId();
        $request['confirmMail'] = $oConfig->getConfigParam('sWcpConfirmMail');

        $request['lang'] = oxRegistry::getLang()->getLanguageAbbr();
        $request['langId'] = oxRegistry::getLang()->getBaseLanguage();
        $request['cl'] = 'wdceepayment';
        $request['fnc'] = 'returnPage';
        $request['pluginVersion'] = $versionString;
        $request['backgroundColor'] = $oConfig->getConfigParam('sWcpBackgroundColor');
        $request['oxid_orderid'] = $oOrder->getId();
        $request['consumerMerchantCrmId'] = md5($oOrder->oxorder__oxbillemail->value);

        if (isset($_SESSION['wcp-consumerDeviceId'])) {
            $request['consumerDeviceId'] = $_SESSION['wcp-consumerDeviceId'];
            unset($_SESSION['wcp-consumerDeviceId']);
        }

        if($paymenttype === 'MASTERPASS') {
            $request['shippingProfile'] = 'NO_SHIPPING';
        }

        if($paymenttype === 'IDL' || $paymenttype === 'EPS') {
            $request['financialInstitution'] = $this->getSession()->getVariable('financialInstitution');
        }

        $oUser = $oOrder->getOrderUser();
        if( $paymenttype === 'INVOICE') {
            if ($oConfig->getConfigParam('sQcpInvoiceProvider') == 'PAYOLUTION') {

                $request = array_merge($request, $this->_getConsumerBillingRequestParams($oOrder));

                if ($paymenttypeShop == "wcp_invoice_b2b" || !empty($oUser->oxuser__oxcompany->value)) {
                    $request['companyVatId'] = $oUser->oxuser__oxustid->value;
                    $request['companyName'] = $oUser->oxuser__oxcompany->value;
                } else {
                    // processing birth date which came from output as array
                    $consumerBirthDate = is_array($oUser->oxuser__oxbirthdate->value) ? $oUser->convertBirthday($oUser->oxuser__oxbirthdate->value) : $oUser->oxuser__oxbirthdate->value;

                    if ($consumerBirthDate != '0000-00-00') {
                        $request['consumerBirthDate'] = $consumerBirthDate;
                    }
                }
            } else {
                $request = array_merge($request, $this->_getOrderBasketRequestParams($oOrder));
                $request = array_merge($request, $this->_getConsumerBillingRequestParams($oOrder));

                $consumerBirthDate = is_array($oUser->oxuser__oxbirthdate->value) ? $oUser->convertBirthday($oUser->oxuser__oxbirthdate->value) : $oUser->oxuser__oxbirthdate->value;
                if ($consumerBirthDate != '0000-00-00') {
                    $request['consumerBirthDate'] = $consumerBirthDate;
                }
            }
        }
        elseif( $paymenttype === 'INSTALLMENT') {
            if ($oConfig->getConfigParam('sQcpInstallmentProvider') == 'PAYOLUTION') {

                $request = array_merge($request, $this->_getConsumerBillingRequestParams($oOrder));

                if ($paymenttypeShop == "wcp_invoice_b2b" || !empty($oUser->oxuser__oxcompany->value)) {
                    $request['companyVatId'] = $oUser->oxuser__oxustid->value;
                    $request['companyName'] = $oUser->oxuser__oxcompany->value;
                } else {
                    // processing birth date which came from output as array
                    $consumerBirthDate = is_array($oUser->oxuser__oxbirthdate->value) ? $oUser->convertBirthday($oUser->oxuser__oxbirthdate->value) : $oUser->oxuser__oxbirthdate->value;

                    if ($consumerBirthDate != '0000-00-00') {
                        $request['consumerBirthDate'] = $consumerBirthDate;
                    }
                }
            } elseif ($oConfig->getConfigParam('sQcpInstallmentProvider') == 'RATEPAY') {
                $request = array_merge($request, $this->_getOrderBasketRequestParams($oOrder));
                $request = array_merge($request, $this->_getConsumerBillingRequestParams($oOrder));

                $consumerBirthDate = is_array($oUser->oxuser__oxbirthdate->value) ? $oUser->convertBirthday($oUser->oxuser__oxbirthdate->value) : $oUser->oxuser__oxbirthdate->value;
                if ($consumerBirthDate != '0000-00-00') {
                    $request['consumerBirthDate'] = $consumerBirthDate;
                }
            }
        }

        if ($oConfig->getConfigParam('bQcpSendAdditionalBasketData') == 1) {
            $request = array_merge($request, $this->_getOrderBasketRequestParams($oOrder));
        }

        if ($oConfig->getConfigParam('bQcpSendAdditionalCustomerData') == 1) {
            $consumerBillingFirstname = $oOrder->oxorder__oxbillfname->value;
            $consumerBillingLastname = $oOrder->oxorder__oxbilllname->value;
            $consumerBillingAddress1 = $oOrder->oxorder__oxbillstreet->value;
            $consumerBillingAddress2 = $oOrder->oxorder__oxbillstreetnr->value;
            $consumerBillingState = $oOrder->oxorder__oxbillstateid->value;
            $consumerBillingZipCode = $oOrder->oxorder__oxbillzip->value;
            $consumerBillingCity = $oOrder->oxorder__oxbillcity->value;
            $consumerBillingCountryId = $oOrder->oxorder__oxbillcountryid->value;
            $oDB = oxDb::GetDB();
            $consumerBillingCountry = $oDB->getOne("select oxisoalpha2 from oxcountry where oxid = '$consumerBillingCountryId'");
            $consumerBillingPhone = $oOrder->oxorder__oxbillfon->value;
            $consumerBillingFax = $oOrder->oxorder__oxbillfax->value;

            $consumerShippingData = $oOrder->getDelAddressInfo();
            if ($consumerShippingData) {
                $consumerShippingFirstname = $consumerShippingData->oxaddress__oxfname->value;
                $consumerShippingLastname = $consumerShippingData->oxaddress__oxlname->value;
                $consumerShippingAddress1 = $consumerShippingData->oxaddress__oxstreet->value;
                $consumerShippingAddress2 = $consumerShippingData->oxaddress__oxstreetnr->value;
                $consumerShippingState = $consumerShippingData->oxaddress__oxstateid->value;
                $consumerShippingZipCode = $consumerShippingData->oxaddress__oxzip->value;
                $consumerShippingCity = $consumerShippingData->oxaddress__oxcity->value;
                $consumerShippingCountryId = $consumerShippingData->oxaddress__oxcountryid->value;
                $consumerShippingCountry = $oDB->getOne("select oxisoalpha2 from oxcountry where oxid = '$consumerShippingCountryId'");
                $consumerShippingPhone = $consumerShippingData->oxaddress__oxfon->value;
                $consumerShippingFax = $consumerShippingData->oxaddress__oxfax->value;
            } else {
                $consumerShippingFirstname = $consumerBillingFirstname;
                $consumerShippingLastname = $consumerBillingLastname;
                $consumerShippingAddress1 = $consumerBillingAddress1;
                $consumerShippingAddress2 = $consumerBillingAddress2;
                $consumerShippingState = $consumerBillingState;
                $consumerShippingZipCode = $consumerBillingZipCode;
                $consumerShippingCity = $consumerBillingCity;
                $consumerShippingCountry = $consumerBillingCountry;
                $consumerShippingPhone = $consumerBillingPhone;
                $consumerShippingFax = $consumerBillingFax;
            }
            $consumerEmail = $oOrder->oxorder__oxbillemail->value;
            $oUser = $oOrder->getOrderUser();

            // processing birth date which came from output as array
            $consumerBirthDate = is_array($oUser->oxuser__oxbirthdate->value) ? $oUser->convertBirthday($oUser->oxuser__oxbirthdate->value) : $oUser->oxuser__oxbirthdate->value;

            $request['consumerBillingFirstname'] = $consumerBillingFirstname;
            $request['consumerBillingLastname'] = $consumerBillingLastname;
            $request['consumerBillingAddress1'] = $consumerBillingAddress1;
            $request['consumerBillingAddress2'] = $consumerBillingAddress2;
            $request['consumerBillingCity'] = $consumerBillingCity;
            $request['consumerBillingCountry'] = $consumerBillingCountry;
            $request['consumerBillingState'] = $consumerBillingState;
            $request['consumerBillingZipCode'] = $consumerBillingZipCode;
            $request['consumerBillingPhone'] = $consumerBillingPhone;
            $request['consumerBillingFax'] = $consumerBillingFax;

            $request['consumerShippingFirstname'] = $consumerShippingFirstname;
            $request['consumerShippingLastname'] = $consumerShippingLastname;
            $request['consumerShippingAddress1'] = $consumerShippingAddress1;
            $request['consumerShippingAddress2'] = $consumerShippingAddress2;
            $request['consumerShippingCity'] = $consumerShippingCity;
            $request['consumerShippingCountry'] = $consumerShippingCountry;
            $request['consumerShippingState'] = $consumerShippingState;
            $request['consumerShippingZipCode'] = $consumerShippingZipCode;
            $request['consumerShippingPhone'] = $consumerShippingPhone;
            $request['consumerShippingFax'] = $consumerShippingFax;
            $request['consumerEmail'] = $consumerEmail;

            if ($consumerBirthDate != '0000-00-00') {
                $request['consumerBirthDate'] = $consumerBirthDate;
            }
            $request['companyVatId'] = $oUser->oxuser__oxustid->value;
            $request['companyName'] = $oUser->oxuser__oxcompany->value;
        }

        $request['checkoutType'] = self::getCheckoutType($paymenttypeShop);
        if ($request['checkoutType'] == 'IFRAME') {
            $request['windowName'] = 'wcpIFrame';
        }

        if ($this->getConfig()->getConfigParam('bQcpDisableDeviceDetection') == 1) {
            $device = self::_getClientDevice();
            if ($device == 'tablet' || $device == 'smartphone') {
                $request['checkoutType'] = 'PAGE';
                if ($request['windowName']) {
                    unset($request['windowName']);
                }
            }
        }

        if ($this->getConfig()->getConfigParam('bQcpUseLayout') == 1) {
            $request['layout'] = self::_getClientDevice();
        }

        $request['sess_challenge'] = oxRegistry::getSession()->getVariable('sess_challenge');

	    $request = array_map('trim', $request);

        $requestFingerprintOrder = 'secret';
        $tempArray = array('secret' => $this->getSecret());

        foreach ($request as $key => $value) {
            $requestFingerprintOrder .= ',' . $key;
            $tempArray[(string)$key] = $value;
        }

        $requestFingerprintOrder .= ',requestFingerprintOrder';
        $tempArray['requestFingerprintOrder'] = $requestFingerprintOrder;

        $hash = hash_init('sha512', HASH_HMAC, $this->getSecret());
        foreach ($tempArray as $key => $value) {
            hash_update($hash, $value);
        }
        $requestFingerprint = hash_final($hash);
        $request['requestFingerprintOrder'] = $requestFingerprintOrder;
        $request['requestFingerprint'] = $requestFingerprint;

        return $request;
    }

    public function returnPage()
    {
        if (isset($_POST['paymentState'])) {
            oxRegistry::getSession()->setVariable('wcpPaymentState', htmlentities($_POST['paymentState']));
        }
        if (isset($_POST['consumerMessage'])) {
            oxRegistry::getSession()->setVariable('wcpPaymentConsumerMessage', htmlentities($_POST['consumerMessage']));
        }
        if (isset($_GET['action']) && $_GET['action'] == 'confirm') {
            $this->_confirmProcess(true);
        } else {
            //fallback if payment status is not updated yet
            $oOrder = $this->_getOrder();

            if ($oOrder->oxorder__oxtransstatus->rawValue !== 'PENDING'
                && $oOrder->oxorder__oxtransstatus->rawValue !== 'CANCELED'
                && $oOrder->oxorder__oxtransstatus->rawValue !== 'PAID'
                && $oOrder->oxorder__oxtransstatus->rawValue !== 'FAILED'
            ) {
                $this->_confirmProcess(false);
            }
            if (isset($_POST['checkoutType']) && $_POST['checkoutType'] == 'IFRAME') {
                $this->_returnIFramePage();
            } else {
                $this->_returnProcess();
            }
        }
    }

    protected function _returnIFramePage()
    {
        $oConfig = $this->getConfig();

        if ($_POST['langId']) {
            $oLang = oxRegistry::get('oxLang');
            $oLang->setBaseLanguage((int)$_POST['langId']);
        }

        $this->_aViewData['wcpReturnUrl'] = html_entity_decode($oConfig->getShopCurrentUrl()) . 'cl=wdceepayment&fnc=returnPage';
        $this->_sThisTemplate = 'page/checkout/qcp_return_iframe.tpl';
    }

    protected function _returnProcess()
    {
        $oOrder = $this->_getOrder();
        $oConfig = $this->getConfig();

        if ($_POST['langId']) {
            $oLang = oxRegistry::get('oxLang');
            $oLang->setBaseLanguage((int)$_POST['langId']);
        }

        if ($oOrder->oxorder__oxtransstatus->value == 'PAID') {
            $sNextStep = 'cl=thankyou';

            oxRegistry::getUtils()->redirect($oConfig->getShopCurrentUrl() . $sNextStep);
        } else {
            if ($oOrder->oxorder__oxtransstatus->value == 'PENDING') {
                $sNextStep = 'cl=thankyou&pending=1';
                oxRegistry::getUtils()->redirect($oConfig->getShopCurrentUrl() . $sNextStep);
            } else {
                $oSession = $this->getSession();

                $paymentState = is_null($oSession->getVariable('wcpPaymentState')) ? 'FAILURE' : $oSession->getVariable('wcpPaymentState');
                $oSession->setBasket(unserialize($oSession->getVariable('wcpBasket')));
                // force oxid to use a new Order for next try.
                $oSession->deleteVariable('sess_challenge');
                // clean up wcpPaymentState
                $oSession->deleteVariable('wcpPaymentState');
                // redirect to payment page with correct error.

                $oLang = oxRegistry::get('oxLang');
                $iLangId = $oLang->getBaseLanguage();

                if ($paymentState == 'CANCEL') {
                    $oSession->setVariable('wcp_payerrortext',
                        $oLang->translateString('qcp_payment_canceled', $iLangId));
                } elseif ($paymentState == 'FAILURE') {
                    $message = $oLang->translateString('qcp_payment_failure', $iLangId);
                    if ($oSession->getVariable('wcpPaymentConsumerMessage')) {
                        $message .= ' (' . $oSession->getVariable('wcpPaymentConsumerMessage') . ')';
                    }

                    $oSession->setVariable('wcp_payerrortext', $message);
                }
                $oSession->deleteVariable('wcpPaymentConsumerMessage');

                self::paymentRedirect($paymentState, $iLangId);
            }
        }
    }

    public static function isValidWCPPayment($sPaymentType)
    {
        return (bool)in_array(str_replace('WCP_', '', strtoupper($sPaymentType)), self::$_VALID_PAYMENT_TYPES);
    }

    /**
     * check if magic quotes gpc or magic quotes runtime are enabled
     *
     * @return bool
     */
    protected function _magicQuotesUsed()
    {
        return (bool)(get_magic_quotes_gpc() || get_magic_quotes_runtime());
    }

    protected function _prepareValueForFingerprint($value)
    {
        return (bool)$this->_magicQuotesUsed() ? stripslashes($value) : $value;
    }

    protected function _getResponseFingerprintSeed($oOrder)
    {
        $tempArray = [];
        $responseFingerprintKeys = explode(',', $_POST['responseFingerprintOrder']);
        foreach ($responseFingerprintKeys as $key) {
            if (strtolower($key) == 'secret') {
                $tempArray[(string)$key] = $this->getSecret();
            } else {
                if (isset($_POST[$key])) {
                    $tempArray[(string)$key] = $this->_prepareValueForFingerprint($_POST[$key]);
                } else {
                    if ($this->_isPaid($oOrder)) {
                        $this->_wcpConfirmLogging('Order has allready been paid.');
                        $this->_wcpConfirmLogging('2nd confirmation attempt failed at fingerPrintSeed creation');
                        return array('', 'Order has allready been paid.');
                    } else {
                        $oOrder->cancelOrder();
                        $failureFolder = $this->getConfig()->getConfigParam('sQcpFailureFolder');
                        $oOrder = $this->_setOrderFolder($oOrder, $failureFolder);
                        if (!$oOrder->save()) {
                            return array('', 'Mandatory fields not used. Order update failed.');
                        } else {
                            return array('', 'Mandatory fields not used.');
                        }
                    }
                }
            }
        }

        $hash = hash_init('sha512', HASH_HMAC, $this->getSecret());

        foreach ($tempArray as $key => $value) {
            hash_update($hash, $value);
        }

        $responseFingerprintSeed = hash_final($hash);

        return array($responseFingerprintSeed, '');
    }

    /**
     * order confirmation.
     * check fingerprint. do some logging.
     * set orderState, cancel order, move to folder.
     * @param boolean showConfirmResponse
     */
    protected function _confirmProcess($showConfirmResponse = true)
    {
        $oOrder = $this->_getOrder();
        $confirmResponseMessage = null;

        if ($_POST['langId']) {
            $oLang = oxRegistry::get('oxLang');
            $oLang->setBaseLanguage((int)$_POST['langId']);
        }

        if (isset($_POST['paymentState'])) {
            if ($_POST['paymentState'] == 'SUCCESS') {

                $this->_wcpConfirmLogging('Payment state: Success - generating response fingerprint seed');
                list($seed, $error) = $this->_getResponseFingerprintSeed($oOrder);
                if ($error) {
                    if ($showConfirmResponse) {
                        die($this->_wcpConfirmResponse($error));
                    } else {
                        return $error;
                    }
                }
                if (strcasecmp($seed, $_POST['responseFingerprint']) == 0) {
                    if (!$this->_isPaid($oOrder)) {
                        $this->_wcpConfirmLogging('Fingerprints match. Setting order status to PAID');

                        $sOXID = $_POST['oxid_orderid'];
                        /** @var qcp_OrderDbGateway $oDbOrder */
                        $oDbOrder = oxNew('qcp_OrderDbGateway');
                        $aOrderData = $oDbOrder->loadByOrderId($sOXID);

                        $aInfo = $_POST;
                        unset($aInfo['responseFingerprint']);
                        unset($aInfo['responseFingerprintOrder']);
                        unset($aInfo['cl']);
                        unset($aInfo['fnc']);
                        unset($aInfo['oxid_orderid']);
                        unset($aInfo['sess_challenge']);
                        unset($aInfo['paymentType']);
                        unset($aInfo['shop_paymenttype']);
                        unset($aInfo['lang']);
                        unset($aInfo['langId']);

                        //add prefix to info keys
                        foreach ($aInfo as $k => $v) {
                            $aInfo['WCP_' . $k] = mysql_real_escape_string($v);
                            unset($aInfo[$k]);
                        }

                        $oOxUserPayment = oxNew("oxUserPayment");
                        $oOxUserPayment->load($oOrder->oxorder__oxpaymentid->value);
                        $oOxUserPayment->oxuserpayments__oxvalue = new oxField(oxRegistry::getUtils()->assignValuesToText($aInfo), oxField::T_RAW);
                        $oOxUserPayment->setDynValues($aInfo);
                        $oOxUserPayment->save();

                        $sClass = "qcp_oxbasket";
                        $oBasket = unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($sClass) . ':"' . $sClass . '"', $aOrderData['BASKET']));
                        $oOrder->sendWirecardCheckoutPageOrderByEmail($oBasket, $oOxUserPayment);
                        $this->_wcpConfirmLogging('Email notification was send.');
                        $oDbOrder->delete($aOrderData['OXID']);

                        $oOrder->oxorder__oxtransstatus = new oxField('PAID');
                        $oOrder->oxorder__oxpaid = new oxField(date('Y-m-d H:i:s'));
                        $orderNumber = mysql_real_escape_string($_POST['orderNumber']);
                        $gatewayReferenceNumber = mysql_real_escape_string($_POST['gatewayReferenceNumber']);
                        $gatewayContractNumber = mysql_real_escape_string($_POST['gatewayContractNumber']);
                        $oOrder->oxorder__oxtransid = new oxField($orderNumber);
                        $oOrder->oxorder__oxpayid = new oxField($gatewayReferenceNumber);
                        $oOrder->oxorder__oxxid = new oxField($gatewayContractNumber);
                        $successFolder = $this->getConfig()->getConfigParam('sQcpSuccessFolder');
                        $oOrder = $this->_setOrderFolder($oOrder, $successFolder);
                        if (!$oOrder->save()) {
                            $this->_wcpConfirmLogging('Order status update failed.');
                            $confirmResponseMessage = 'Order status update failed.';
                        }

                        if ($showConfirmResponse) {
                            die($this->_wcpConfirmResponse($confirmResponseMessage));
                        }
                    } else {
                        $oxEmail = oxnew('oxemail');
                        $oxEmail->sendWCPDoublePaymentMail($oOrder->oxorder__oxordernr->value,
                            $oOrder->oxorder_oxtransid->rawValue, mysql_real_escape_string($_POST['orderNumber']));
                        if ($showConfirmResponse) {
                            die($this->_wcpConfirmResponse());
                        }
                    }
                } else {
                    $oOrder->cancelOrder();
                    $oOrder->oxorder__oxtransstatus = new oxField('FAILED');
                    $failureFolder = $this->getConfig()->getConfigParam('sQcpFailureFolder');
                    $oOrder = $this->_setOrderFolder($oOrder, $failureFolder);

                    $confirmResponseMessage = $oOrder->save() ? 'Fingerprint is Invalid.' : 'Fingerprint is Invalid. Order status update failed.';
                    if ($showConfirmResponse) {
                        die($confirmResponseMessage);
                    } else {
                        return $confirmResponseMessage;
                    }
                }
            } else {
                if ($_POST['paymentState'] == 'PENDING') {
                    $this->_wcpConfirmLogging('Payment state: Pending - generating response fingerprint seed');

                    list($seed, $error) = $this->_getResponseFingerprintSeed($oOrder);
                    if ($error) {
                        if ($showConfirmResponse) {
                            die($this->_wcpConfirmResponse($error));
                        } else {
                            return $error;
                        }
                    }

                    if (strcasecmp($seed, $_POST['responseFingerprint']) == 0) {
                        if (!$this->_isPaid($oOrder)) {
                            $this->_wcpConfirmLogging('Fingerprints match. Setting order status to PENDING');

                            $sendEmail = !in_array($oOrder->oxorder__oxtransstatus, array('PENDING'));

                            $sOXID = $_POST['oxid_orderid'];
                            /** @var qcp_OrderDbGateway $oDbOrder */
                            $oDbOrder = oxNew('qcp_OrderDbGateway');
                            $aOrderData = $oDbOrder->loadByOrderId($sOXID);

                            $aInfo = $_POST;
                            unset($aInfo['responseFingerprint']);
                            unset($aInfo['responseFingerprintOrder']);
                            unset($aInfo['cl']);
                            unset($aInfo['fnc']);
                            unset($aInfo['oxid_orderid']);
                            unset($aInfo['sess_challenge']);
                            unset($aInfo['paymentType']);
                            unset($aInfo['shop_paymenttype']);

                            //add prefix to info keys
                            foreach ($aInfo as $k => $v) {
                                $aInfo['WCP_' . $k] = mysql_real_escape_string($v);
                                unset($aInfo[$k]);
                            }

                            $oOxUserPayment = oxNew("oxUserPayment");
                            $oOxUserPayment->load($oOrder->oxorder__oxpaymentid->value);
                            $oOxUserPayment->oxuserpayments__oxvalue = new oxField(oxRegistry::getUtils()->assignValuesToText($aInfo), oxField::T_RAW);
                            $oOxUserPayment->setDynValues($aInfo);
                            $oOxUserPayment->save();

                            $oOrder->oxorder__oxtransstatus = new oxField('PENDING');
                            $pendingFolder = $this->getConfig()->getConfigParam('sQcpPendingFolder');
                            $oOrder = $this->_setOrderFolder($oOrder, $pendingFolder);
                            if (!$oOrder->save()) {
                                $this->_wcpConfirmLogging('Order status update failed.');
                                $confirmResponseMessage = 'Order status update failed.';
                            }

                            $sClass = "qcp_oxbasket";
                            $oBasket = unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($sClass) . ':"' . $sClass . '"', $aOrderData['BASKET']));
                            if($sendEmail) {
                                $oOrder->sendWirecardCheckoutPageOrderByEmail($oBasket, $oOxUserPayment);
                                $this->_wcpConfirmLogging('Email notification was send.');
                            }

                            if ($showConfirmResponse) {
                                die($this->_wcpConfirmResponse($confirmResponseMessage));
                            }
                        } else {
                            $oxEmail = oxnew('oxemail');
                            $oxEmail->sendWCPDoublePaymentMail($oOrder->oxorder__oxordernr->value,
                            $oOrder->oxorder_oxtransid->rawValue, mysql_real_escape_string($_POST['orderNumber']));
                            if ($showConfirmResponse) {
                                die($this->_wcpConfirmResponse());
                            }
                        }
                    } else {
                        $oOrder->cancelOrder();
                        $oOrder->oxorder__oxtransstatus = new oxField('FAILED');
                        $failureFolder = $this->getConfig()->getConfigParam('sQcpFailureFolder');
                        $oOrder = $this->_setOrderFolder($oOrder, $failureFolder);

                        $confirmResponseMessage = $oOrder->save() ? 'Fingerprint is Invalid.' : 'Fingerprint is Invalid. Order status update failed.';

                        if ($showConfirmResponse) {
                            die($this->_wcpConfirmResponse($confirmResponseMessage));
                        }
                    }
                } else {
                    if (!$this->_isPaid($oOrder)) {
                        if ($_POST['paymentState'] == 'CANCEL') {
                            $oOrder->cancelOrder();
                            $oOrder->oxorder__oxtransstatus = new oxField('CANCELED');
                            $cancelFolder = $this->getConfig()->getConfigParam('sQcpCancelFolder');
                            $oOrder = $this->_setOrderFolder($oOrder, $cancelFolder);
                            if (!$oOrder->save()) {
                                $confirmResponseMessage = 'Order status update failed.';
                            }

                            if ($showConfirmResponse) {
                                die($this->_wcpConfirmResponse($confirmResponseMessage));
                            }
                        } else {
                            if ($_POST['paymentState'] == 'FAILURE') {
                                $oOrder->cancelOrder();
                                $oOrder->oxorder__oxtransstatus = new oxField('FAILED');
                                $failureFolder = $this->getConfig()->getConfigParam('sQcpFailureFolder');
                                $oOrder = $this->_setOrderFolder($oOrder, $failureFolder);

                                if (!$oOrder->save()) {
                                    $confirmResponseMessage = 'Order status update failed.';
                                }

                                if ($showConfirmResponse) {
                                    die($this->_wcpConfirmResponse($confirmResponseMessage));
                                }
                            } else {
                                // paymentState unknown - not paid yet. handle as failure.
                                $this->_wcpConfirmLogging('Invalid payment state. Set failure state for order');
                                $oOrder->cancelOrder();
                                $oOrder->oxorder__oxtransstatus = new oxField('FAILED');
                                $failureFolder = $this->getConfig()->getConfigParam('sQcpFailureFolder');
                                $oOrder = $this->_setOrderFolder($oOrder, $failureFolder);

                                if (!$oOrder->save()) {
                                    $confirmResponseMessage = 'Order status update failed.';
                                }

                                if ($showConfirmResponse) {
                                    die($this->_wcpConfirmResponse($confirmResponseMessage));
                                }
                            }
                        }
                    } else {

                        // order has already been saved. no matter what comes
                        // next we have a valid payment
                        $this->_wcpConfirmLogging('order has already been paid.');
                        if ($showConfirmResponse) {
                            die($this->_wcpConfirmResponse());
                        }
                    }
                }
            }
        } else {
            if ($showConfirmResponse) {
                die($this->_wcpConfirmResponse('Invalid call of confirm action. no payment state given'));
            }
        }
    }

    protected function _wcpConfirmResponse($message = null)
    {
        if (!is_null($message)) {
            $this->_wcpConfirmLogging($message);
            $value = 'result="NOK" message="' . $message . '" ';
        } else {
            $this->_wcpConfirmLogging('Success confirmation will be delivered');
            $value = 'result="OK"';
        }

        return '<WCP-CONFIRMATION-RESPONSE ' . $value . ' />';
    }

    protected function _setOrderFolder($oOrder, $folder)
    {
        if ($folder) {
            $oOrder->oxorder__oxfolder = new oxField($folder, oxField::T_RAW);
            $this->_wcpConfirmLogging('moved order to ' . $folder . ' folder');
        }

        return $oOrder;
    }

    /**
     * check if order is payed
     *
     * @var $oOrder
     * @return boolean
     */
    protected function _isPaid($oOrder)
    {
        if ($oOrder->oxorder__oxtransstatus->value == 'PAID') {
            $this->_wcpConfirmLogging('Order has already been paid.');

            return true;
        }

        return false;
    }

    /**
     * Redirect to index.php?cl=payment
     *
     * @param $state (optional)
     */
    public static function paymentRedirect($state = null, $lang = null)
    {

        $sNextStep = oxRegistry::getConfig()->getShopCurrentUrl() . 'cl=payment';
        if ($lang) {
            $sNextStep .= '&lang=' . (int)$lang;
        }

        if (!is_null($state)) {
            $errorCode = ($state == 'CANCEL') ? self::$_PAYMENT_CANCELED_ERRORCODE : self::$_PAYMENT_FAILED_ERRORCODE;
            $sNextStep .= '&payerror=' . $errorCode;
            oxRegistry::getUtils()->redirect($sNextStep);
        } else {
            oxRegistry::getUtils()->redirect($sNextStep);
        }
    }

    private static function _getClientDevice()
    {
        $detect = new WirecardCEE_MobileDetect;

        return ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'smartphone') : 'desktop');
    }

    private function _getOrderBasketRequestParams($oOrder)
    {
        $oOrderArticles = $oOrder->getOrderArticles();

        $basketItemsCount = 0;

        $oLang = oxRegistry::get('oxLang');
        $iLangId = $oLang->getBaseLanguage();
        /**
         * @var $oOrderArticle oxOrderArticle
         */
        foreach ($oOrderArticles as $oOrderArticle) {
            $amount = $oOrderArticle->oxorderarticles__oxamount->rawValue;
            $picture_url = $oOrderArticle->getConfig()->getOutUrl() . "pictures/master/product/1/" . $oOrderArticle->oxorderarticles__oxpic1->rawValue;
            $basketItemsCount++;

            $basket['basketItem' . $basketItemsCount . 'ArticleNumber'] = $oOrderArticle->oxorderarticles__oxartnum->rawValue;
            $basket['basketItem' . $basketItemsCount . 'Description'] = substr(utf8_decode($oOrderArticle->oxarticles__oxshortdesc->rawValue), 0, 127);
            $basket['basketItem' . $basketItemsCount . 'ImageUrl'] = $picture_url;
            $basket['basketItem' . $basketItemsCount . 'Name'] = substr(utf8_decode($oOrderArticle->oxarticles__oxtitle->value), 0, 127);
            $basket['basketItem' . $basketItemsCount . 'Quantity'] = $amount;
            $basket['basketItem' . $basketItemsCount . 'UnitGrossAmount'] = number_format($oOrderArticle->oxorderarticles__oxbprice->rawValue, 2);
            $basket['basketItem' . $basketItemsCount . 'UnitNetAmount'] = number_format($oOrderArticle->oxorderarticles__oxnprice->rawValue, 2);
            $basket['basketItem' . $basketItemsCount . 'UnitTaxAmount'] = number_format($oOrderArticle->oxorderarticles__oxvatprice->rawValue, 2);
            $basket['basketItem' . $basketItemsCount . 'UnitTaxRate'] = number_format($oOrderArticle->oxorderarticles__oxvat->rawValue, 2);

            //add possible additional pcosts as articles to basket
            $aAdditionalCosts = array(
                'shipping cost' => array(
                    'description' => $oLang->translateString('SHIPPING_COST', $iLangId),
                    'vat' => $oOrder->oxorder__oxdelvat->rawValue,
                    'price' => $oOrder->oxorder__oxdelcost->rawValue
                ),
                'paymethod cost' => array(
                    'description' => $oLang->translateString('SURCHARGE',
                            $iLangId) . ' ' . $oLang->translateString('PAYMENT_METHOD', $iLangId),
                    'vat' => $oOrder->oxorder__oxpayvat->rawValue,
                    'price' => $oOrder->oxorder__oxpaycost->rawValue
                ),
                'wrapping cost' => array(
                    'description' => $oLang->translateString('GIFT_WRAPPING', $iLangId),
                    'vat' => $oOrder->oxorder__oxwrapvat->rawValue,
                    'price' => $oOrder->oxorder__oxwrapvat->rawValue
                ),
                'gift card cost' => array(
                    'description' => $oLang->translateString('GREETING_CARD', $iLangId),
                    'vat' => $oOrder->oxorder__oxgiftcardvat->rawValue,
                    'price' => $oOrder->oxorder__oxgiftcardcost->rawValue
                ),
                'discount' => array(
                    'description' => $oLang->translateString('DISCOUNT', $iLangId),
                    'vat' => 0,
                    'price' => $oOrder->oxorder__oxdiscount->rawValue * -1
                ),
            );

            foreach ($aAdditionalCosts as $type => $data) {
                if ($data['price'] != 0) {
                    $basketItemsCount++;
                    $netTaxAdditional = number_format($data['price'] * ($data['vat'] / 100), 2);
                    $netPriceAdditional = number_format($data['price'] - $netTaxAdditional, 2);
                    $basket['basketItem' . $basketItemsCount . 'ArticleNumber'] = $type;
                    $basket['basketItem' . $basketItemsCount . 'Description'] = $data['description'];
                    $basket['basketItem' . $basketItemsCount . 'Name'] = $data['description'];
                    $basket['basketItem' . $basketItemsCount . 'Quantity'] = 1;
                    $basket['basketItem' . $basketItemsCount . 'UnitGrossAmount'] = number_format($data['price'], 2);
                    $basket['basketItem' . $basketItemsCount . 'UnitNetAmount'] = number_format($netPriceAdditional, 2);
                    $basket['basketItem' . $basketItemsCount . 'UnitTaxAmount'] = number_format($netTaxAdditional, 2);
                    $basket['basketItem' . $basketItemsCount . 'UnitTaxRate'] = number_format($data['vat'], 2);
                }
            }
        }
        return $basket;
    }

    private function _getConsumerBillingRequestParams($oOrder)
    {

        $consumerBillingFirstname = $oOrder->oxorder__oxbillfname->value;
        $consumerBillingLastname = $oOrder->oxorder__oxbilllname->value;
        $consumerBillingAddress1 = $oOrder->oxorder__oxbillstreet->value;
        $consumerBillingAddress2 = $oOrder->oxaddress__oxstreetnr->value;
        $consumerBillingZipCode = $oOrder->oxorder__oxbillzip->value;
        $consumerBillingCity = $oOrder->oxorder__oxbillcity->value;
        $consumerBillingCountryId = $oOrder->oxorder__oxbillcountryid->value;
        $oDB = oxDb::GetDB();
        $consumerBillingCountry = $oDB->getOne("select oxisoalpha2 from oxcountry where oxid = '$consumerBillingCountryId'");
        $consumerEmail = $oOrder->oxorder__oxbillemail->value;

        $billing['consumerBillingFirstname'] = $consumerBillingFirstname;
        $billing['consumerBillingLastname'] = $consumerBillingLastname;
        $billing['consumerBillingAddress1'] = $consumerBillingAddress1;
        $billing['consumerBillingAddress2'] = $consumerBillingAddress2;
        $billing['consumerBillingCity'] = $consumerBillingCity;
        $billing['consumerBillingCountry'] = $consumerBillingCountry;
        $billing['consumerBillingZipCode'] = $consumerBillingZipCode;
        $billing['consumerEmail'] = $consumerEmail;

        return $billing;
    }

    public static function getCheckoutType($sPaymentType)
    {

        $config = oxRegistry::getConfig();

        if ($config->getConfigParam('bQcpDisableDeviceDetection') == 1) {
            $device = self::_getClientDevice();
            if ($device == 'tablet' || $device == 'smartphone') {

                return 'PAGE';
            }
        }

        return ($config->getConfigParam(sprintf('bQcp_%s_UseIframe',
                strtolower($sPaymentType))) == 1) ? "IFRAME" : "PAGE";
    }
}
