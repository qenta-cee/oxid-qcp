<?php

/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/plugins/#legalNotice
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
 */

class qcp_submit_config extends oxAdminView
{
    protected $_sThisTemplate = 'qcp_submit_config.tpl';

    protected $_aSupportMails = 'support@qenta.com';

    /**
     * Executes parent method parent::render() and returns name of template
     * file "qcp_submit_config.tpl".
     *
     * @return string
     */

    public function render()
    {
        parent::render();

        $sCurrentAdminShop = oxRegistry::getSession()->getVariable("currentadminshop");

        if (!$sCurrentAdminShop) {
            if (oxRegistry::getSession()->getVariable("malladmin")) {
                $sCurrentAdminShop = "oxbaseshop";
            } else {
                $sCurrentAdminShop = oxRegistry::getSession()->getVariable("actshop");
            }
        }

        $this->_aViewData["currentadminshop"] = $sCurrentAdminShop;
        oxRegistry::getSession()->setVariable("currentadminshop", $sCurrentAdminShop);

        $recipient = oxRegistry::getConfig()->getRequestParameter('qcp_config_export_recipient');
        $comment = oxRegistry::getConfig()->getRequestParameter('qcp_config_export_description_text');
        $replyTo = oxRegistry::getConfig()->getRequestParameter('qcp_config_export_reply_to_mail');

        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
        $oSmarty->assign("aSupportMails", $this->_aSupportMails);
        if (!empty($recipient)) {
            $oSmarty->assign("sSupportMailActive", $recipient);
        }
        if (!empty($comment)) {
            $oSmarty->assign("sDescriptionText", $comment);
        }
        if (!empty($replyTo)) {
            $oSmarty->assign("sReplyTo", $replyTo);
        }

        return $this->_sThisTemplate;
    }

    public function getModuleConfig()
    {
        $oConfig = oxRegistry::getConfig();
        $aModules = $oConfig->getConfigParam('aModulePaths');

        include('../modules/' . $aModules['qentacheckoutpage'] . '/metadata.php');

        foreach ($aModule['settings'] as $k => $aParams) {
            if ($aParams['name'] !== 'sQcpSecret') {
                $params[$aParams['name']] = $oConfig->getConfigParam($aParams['name']);
            } else {
                $params[$aParams['name']] = str_pad('', strlen($oConfig->getConfigParam($aParams['name'])), 'X');
            }
        }

        $moduleConfigString = "module extending classes\n";
        $moduleConfigString .= "------------------------\n";
        $moduleConfigString .= print_r($oConfig->getModulesWithExtendedClass(), 1) . "\n";
        $moduleConfigString .= "\n\nmodule config\n";
        $moduleConfigString .= "------------------------\n";
        $moduleConfigString .= print_r($params, 1) . "\n";

        return $moduleConfigString;
    }

    public function submit()
    {
        $recipient = oxRegistry::getConfig()->getRequestParameter('qcp_config_export_recipient');
        $confString = $this->getModuleConfig();
        $comment = oxRegistry::getConfig()->getRequestParameter('qcp_config_export_description_text');
        $replyTo = oxRegistry::getConfig()->getRequestParameter('qcp_config_export_reply_to_mail');
        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();

        if (empty($recipient) || !in_array($recipient, $this->_aSupportMails)) {
            $oSmarty->assign("sErrorMessage", 'recipient invalid.');

            return;
        }

        $Mail = oxRegistry::get('oxemail');
        $Mail->setFrom(
            oxRegistry::getConfig()->getActiveShop()->oxshops__oxowneremail->rawValue,
            oxRegistry::getConfig()->getActiveShop()->oxshops__oxname->rawValue
        );
        $Mail->setRecipient($recipient);
        $Mail->setBody('<p>' . $confString . '</p><p>' . $comment . '</p>');
        $Mail->setAltBody($confString . "\n\n" . $comment);
        $Mail->setSubject('OXID QCP Plugin Configuration from ' . oxRegistry::getConfig()->getActiveShop()->oxshops__oxname->rawValue);
        if ($replyTo) {
            $Mail->setReplyTo($replyTo, "");
        }

        if ($Mail->send()) {
            $oSmarty->assign("sSuccessMessage", 'SUCCESS');
        }
    }
}
