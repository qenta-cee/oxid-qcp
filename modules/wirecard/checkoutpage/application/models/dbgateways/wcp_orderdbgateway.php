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
 * Order db gateway class
 */
class wcp_OrderDbGateway
{
    /**
     * Save order to database
     *
     * @param array $aData
     *
     * @return bool
     */
    public function insert($aData)
    {
        $oDb = $this->_getDb();

        $aData['OXID'] = oxUtilsObject::getInstance()->generateUID();
        foreach ($aData as $sField => $sData) {
            $aSql[] = '`' . $sField . '` = ' . $oDb->quote($sData);
        }

        $sSql = 'INSERT INTO `wirecardcheckoutpage_order` SET ';
        $sSql .= implode(', ', $aSql);

        $oDb->execute($sSql);

        return $aData['OXID'];
    }

    /**
     *
     * @param string $sOrderId Order id.
     *
     * @return array
     */
    public function loadByOrderId($sOrderId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getRow('SELECT * FROM `wirecardcheckoutpage_order` WHERE `OXORDERID` = ' . $oDb->quote($sOrderId));

        return $aData;
    }

    /**
     * @param string $sOxid .
     *
     * @return bool
     */
    public function delete($sOxid)
    {
        $oDb = $this->_getDb();

        $blResult = $oDb->execute('DELETE FROM `wirecardcheckoutpage_order` WHERE `OXID` = ' . $oDb->quote($sOxid));

        return $blResult;
    }

    /**
     * Returns data base resource.
     *
     * @return oxLegacyDb
     */
    protected function _getDb()
    {
        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
    }

}
