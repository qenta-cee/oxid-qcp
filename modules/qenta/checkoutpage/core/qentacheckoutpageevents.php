<?php

/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/shop_plugins/info/
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
 */

class qentaCheckoutPageEvents
{
    public static function getAvailablePaymenttypes()
    {
        return array(
            'CCARD' => array('weight' => 1, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 1),
            'CCARD-MOTO' => array('weight' => 2, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'MAESTRO' => array('weight' => 3, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'EPS' => array('weight' => 4, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'IDL' => array('weight' => 5, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'GIROPAY' => array('weight' => 6, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'TATRAPAY' => array('weight' => 7, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'TRUSTPAY' => array('weight' => 8, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'SOFORTUEBERWEISUNG' => array(
                'weight' => 9,
                'fromamount' => 0,
                'toamount' => 100000,
                'activatePaymethod' => 1
            ),
            'SKRILLWALLET' => array('weight' => 11, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'MASTERPASS' => array('weight' => 12, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'BANCONTACT_MISTERCASH' => array(
                'weight' => 13,
                'fromamount' => 0,
                'toamount' => 100000,
                'activatePaymethod' => 0
            ),
            'PRZELEWY24' => array('weight' => 14, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'MONETA' => array('weight' => 15, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'POLI' => array('weight' => 16, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'EKONTO' => array('weight' => 17, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'TRUSTLY' => array('weight' => 18, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'PBX' => array('weight' => 19, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'PSC' => array('weight' => 20, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'PAYPAL' => array('weight' => 22, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 1),
            'EPAY_BG' => array('weight' => 23, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'SEPA-DD' => array('weight' => 24, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 1),
            'INVOICE_B2C' => array('weight' => 25, 'fromamount' => 10, 'toamount' => 3500, 'activatePaymethod' => 1),
            'INVOICE_B2B' => array('weight' => 26, 'fromamount' => 25, 'toamount' => 3500, 'activatePaymethod' => 1),
            'INSTALLMENT' => array('weight' => 27, 'fromamount' => 150, 'toamount' => 3500, 'activatePaymethod' => 0),
            'VOUCHER' => array('weight' => 28, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
            'SELECT' => array('weight' => 29, 'fromamount' => 0, 'toamount' => 100000, 'activatePaymethod' => 0),
        );
    }

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        self::addQentaCheckoutPageOrderTable();
        self::addPaymentTypes();
    }

    /**
     * Execute action on deactivate event
     *
     * @return null
     */
    public static function onDeactivate()
    {
        self::disablePaymenttypes();
    }

    public static function addPaymentTypes()
    {
        /** @var oxLang $oLang */
        $oLang = oxRegistry::get('oxLang');
        $aLanguages = $oLang->getLanguageIds();

        foreach (self::getAvailablePaymenttypes() as $wpt => $configValues) {
            $trkey = sprintf('QENTA_CHECKOUT_PAGE_%s', strtoupper($wpt));
            $pt = sprintf('%s_%s', 'qcp', strtolower($wpt));

            /** @var oxPayment $oPayment */
            $oPayment = oxNew('oxPayment');
            $oPayment->setId($pt);
            $oPayment->oxpayments__oxactive = new oxField($configValues['activatePaymethod']);
            $oPayment->oxpayments__oxaddsum = new oxField(0);
            $oPayment->oxpayments__oxaddsumtype = new oxField('abs');
            $oPayment->oxpayments__oxfromboni = new oxField(0);
            $oPayment->oxpayments__oxfromamount = new oxField($configValues['fromamount']);
            $oPayment->oxpayments__oxtoamount = new oxField($configValues['toamount']);
            $oPayment->oxpayments__oxsort = new oxField($configValues['weight']);

            foreach ($aLanguages as $iLanguageId => $sLangCode) {
                $oPayment->setLanguage($iLanguageId);
                $oPayment->oxpayments__oxlongdesc = new oxField($oLang->translateString(
                    $trkey . '_DESC',
                    $iLanguageId
                ));
                $paymethodName = $oLang->translateString($trkey . '_LABEL', $iLanguageId);
                $oPayment->oxpayments__oxdesc = new oxField('QCP ' . $paymethodName);
                $oPayment->save();
            }
        }
    }

    /**
     * Disables payment methods
     */
    public static function disablePaymenttypes()
    {
        foreach (self::getAvailablePaymenttypes() as $pt => $configValues) {
            $pt = sprintf('%s_%s', 'qcp', strtolower($pt));
            /** @var oxPayment $oPayment */
            $oPayment = oxNew('oxpayment');
            $oPayment->load($pt);
            $oPayment->oxpayments__oxactive = new oxField(0);
            $oPayment->save();
        }
    }

    public static function addQentaCheckoutPageOrderTable()
    {
        $sSql = "CREATE TABLE IF NOT EXISTS `qentacheckoutpage_order` (
              `OXID` char(32) NOT NULL,
              `OXORDERID` char(32) NOT NULL,
              `BASKET` TEXT NULL,
              `TIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
              PRIMARY KEY (`OXID`),
              KEY `QENTACHECKOUTPAGE_ORDER_OXORDERID` (`OXORDERID`)
            );";

        oxDb::getDb()->execute($sSql);
    }
}
