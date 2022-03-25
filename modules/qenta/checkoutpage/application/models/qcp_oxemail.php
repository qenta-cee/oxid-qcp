<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/plugins/#legalNotice
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

class qcp_oxemail extends qcp_oxemail_parent
{
    /**
     * two qcp payments for order template
     *
     * @var string
     */
    protected $_sQCPDoublePaymentForOrder = "email/html/qcpDoublePaymentForOrder.tpl";


    /**
     * Sends two payments for order notification email to shop owner.
     *
     * @param string $sOrderNumber
     * @param string $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendQCPDoublePaymentMail($sOrderNumber, $sQCPOrderNumber1, $sQCPOrderNumber2, $sSubject = null)
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
        $oSmarty->assign("qcpOrderNumber1", $sQCPOrderNumber1);
        $oSmarty->assign("qcpOrderNumber2", $sQCPOrderNumber2);

        $oOutputProcessor = oxNew("oxoutput");
        $aNewSmartyArray = $oOutputProcessor->processViewArray($oSmarty->get_template_vars(), "oxemail");

        foreach ($aNewSmartyArray as $key => $val) {
            $oSmarty->assign($key, $val);
        }

        $this->setRecipient($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());
        $this->setFrom($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());
        $this->setBody($oSmarty->fetch($this->getConfig()->getTemplatePath($this->_sQCPDoublePaymentForOrder, false)));
        $this->setAltBody("");
        $this->setSubject(($sSubject !== null) ? $sSubject : $oLang->translateString('QCP_EMAIL_DOUBLE_PAYMENT_HEADLINE'));

        $blSend = $this->send();

        return $blSend;
    }

    public function getLanguageInstance()
    {
        $oLang = oxRegistry::getLang();

        return $oLang;
    }
}
