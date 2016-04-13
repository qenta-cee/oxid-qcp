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

class wcp_oxemail extends wcp_oxemail_parent
{
    /**
     * two wcp payments for order template
     *
     * @var string
     */
    protected $_sWCPDoublePaymentForOrder = "email/html/wcpDoublePaymentForOrder.tpl";


    /**
     * Sends two payments for order notification email to shop owner.
     *
     * @param string $sOrderNumber
     * @param string $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendWCPDoublePaymentMail($sOrderNumber, $sWCPOrderNumber1, $sWCPOrderNumber2, $sSubject = null)
    {
        $myConfig = $this->getConfig();
        $blSend = false;

        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp... )
        $this->_setMailParams($oShop);
        /**
         * @var oxLang
         */
        $oLang = oxRegistry::getLang();

        $oSmarty = $this->_getSmarty();

        $oSmarty->assign("charset", $oLang->translateString("charset"));
        $oSmarty->assign("shop", $oShop);
        $oSmarty->assign("oViewConf", $oShop);
        $oSmarty->assign("oView", $myConfig->getActiveView());
        $oSmarty->assign("orderNumber", $sOrderNumber);
        $oSmarty->assign("wcpOrderNumber1", $sWCPOrderNumber1);
        $oSmarty->assign("wcpOrderNumber2", $sWCPOrderNumber2);

        $oOutputProcessor = oxNew("oxoutput");
        $aNewSmartyArray = $oOutputProcessor->processViewArray($oSmarty->get_template_vars(), "oxemail");

        foreach ($aNewSmartyArray as $key => $val) {
            $oSmarty->assign($key, $val);
        }

        $this->setRecipient($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());
        $this->setFrom($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());
        $this->setBody($oSmarty->fetch($this->getConfig()->getTemplatePath($this->_sWCPDoublePaymentForOrder, false)));
        $this->setAltBody("");
        $this->setSubject(($sSubject !== null) ? $sSubject : $oLang->translateString('WCP_EMAIL_DOUBLE_PAYMENT_HEADLINE'));

        $blSend = $this->send();

        return $blSend;
    }

    public function getLanguageInstance()
    {
        $oLang = oxRegistry::getLang();

        return $oLang;
    }
}
