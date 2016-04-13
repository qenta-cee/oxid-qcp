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

class wcp_submit_config extends oxAdminView
{
    protected $_sThisTemplate = 'wcp_submit_config.tpl';

    protected $_aSupportMails = array('support.at@wirecard.com', 'support@wirecard.com');

    /**
     * Executes parent method parent::render() and returns name of template
     * file "wcp_submit_config.tpl".
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

        $recipient = oxRegistry::getConfig()->getRequestParameter('wcp_config_export_recipient');
        $comment = oxRegistry::getConfig()->getRequestParameter('wcp_config_export_description_text');
        $replyTo = oxRegistry::getConfig()->getRequestParameter('wcp_config_export_reply_to_mail');

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

        include('../modules/' . $aModules['wirecardcheckoutpage'] . '/metadata.php');

        foreach ($aModule['settings'] as $k => $aParams) {
            if ($aParams['name'] !== 'sWcpSecret') {
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
        $recipient = oxRegistry::getConfig()->getRequestParameter('wcp_config_export_recipient');
        $confString = $this->getModuleConfig();
        $comment = oxRegistry::getConfig()->getRequestParameter('wcp_config_export_description_text');
        $replyTo = oxRegistry::getConfig()->getRequestParameter('wcp_config_export_reply_to_mail');
        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();

        if (empty($recipient) || !in_array($recipient, $this->_aSupportMails)) {
            $oSmarty->assign("sErrorMessage", 'recipient invalid.');

            return;
        }

        $Mail = oxRegistry::get('oxemail');
        $Mail->setFrom(oxRegistry::getConfig()->getActiveShop()->oxshops__oxowneremail->rawValue,
            oxRegistry::getConfig()->getActiveShop()->oxshops__oxname->rawValue);
        $Mail->setRecipient($recipient);
        $Mail->setBody('<p>' . $confString . '</p><p>' . $comment . '</p>');
        $Mail->setAltBody($confString . "\n\n" . $comment);
        $Mail->setSubject('OXID WCP Plugin Configuration from ' . oxRegistry::getConfig()->getActiveShop()->oxshops__oxname->rawValue);
        if ($replyTo) {
            $Mail->setReplyTo($replyTo, "");
        }

        if ($Mail->send()) {
            $oSmarty->assign("sSuccessMessage", 'SUCCESS');
        }
    }
}