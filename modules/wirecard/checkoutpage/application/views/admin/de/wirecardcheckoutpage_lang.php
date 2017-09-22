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

$sLangName = "Deutsch";

$aLang = array(
    "charset" => 'UTF-8',
    'SHOP_MODULE_GROUP_wcp_parameters' => 'Parameter Einstellungen',
    'SHOP_MODULE_sWcpCustomerId' => 'Kundennummer',
    'HELP_SHOP_MODULE_sWcpCustomerId' => 'Ihre Wirecard-Kundennummer (customerId, im Format D2#####).<br /><a href="https://guides.wirecard.at/request_parameters#customerid" target="_blank">Weitere Informationen: customerId</a>',
    'SHOP_MODULE_sWcpShopId' => 'Shop ID',
    'HELP_SHOP_MODULE_sWcpShopId' => 'Shop-Kennung (<a href="https://guides.wirecard.at/request_parameters#shopId" target="_blank">shopId</a>) bei mehreren Onlineshops. Bitte kontaktieren Sie unsere <a href="https://guides.wirecard.at/sales" target="_blank">Sales-Teams</a> um dieses Feature freizuschalten.<br /><a href="https://guides.wirecard.at/request_parameters#shopid" target="_blank">Weitere Informationen: shopId</a>',
    'SHOP_MODULE_sWcpSecret' => 'Secret',
    'HELP_SHOP_MODULE_sWcpSecret' => 'Geheime Zeichenfolge, die Sie von Wirecard erhalten haben, zum Signieren und Validieren von Daten zur Pr&uuml;fung der Authentizit&auml;t.<br /><a href="https://guides.wirecard.at/security:start#secret_and_fingerprint" target="_blank">Weitere Informationen: secret</a>',
    'SHOP_MODULE_sWcpImageUrl' => 'URL des Bildes auf der Bezahlseite',
    'HELP_SHOP_MODULE_sWcpImageUrl' => 'URL zu einem Bild/Logo, das w&auml;hrend des Bezahlprozesses in Wirecard Checkout Page angezeigt wird.<a href="https://guides.wirecard.at/request_parameters#imageurl" target="_blank">Weitere Informationen: imageUrl</a>',
    'SHOP_MODULE_sWcpServiceUrl' => 'URL zur Impressum-Seite',
    'HELP_SHOP_MODULE_sWcpServiceUrl' => 'URL auf der Bezahlseite, die zur Impressum-Seite des Onlineshops f&uuml;hrt.<a href="https://guides.wirecard.at/request_parameters#serviceurl" target="_blank">Weitere Informationen: serviceUrl</a>',
    'SHOP_MODULE_sWcpDisplayText' => 'Text auf der Bezahlseite',
    'HELP_SHOP_MODULE_sWcpDisplayText' => 'Text, der w&auml;hrend des Bezahlprozesses angezeigt wird, z.B. "Danke f&uuml;r Ihre Bestellung in xy-Shop."<a href="https://guides.wirecard.at/request_parameters#displaytext" target="_blank">Weitere Informationen: displayText</a>',
    'SHOP_MODULE_bWcpAutoDeposit' => 'Automatisches Abbuchen',
    'HELP_SHOP_MODULE_bWcpAutoDeposit' => 'Automatisches Abbuchen der Zahlungen.  <a href="https://guides.wirecard.at/request_parameters#autodeposit" target="_blank">Weitere Informationen</a>.  Bitte kontaktieren Sie unsere <a href="https://guides.wirecard.at/sales" target="_blank">Sales-Teams</a> um dieses Feature freizuschalten.',
    'SHOP_MODULE_sWcpBackgroundColor' => 'Hintergrundfarbe f&uuml;r Kreditkarten-Logos',
    'HELP_SHOP_MODULE_sWcpBackgroundColor' => 'Hintergrundfarbe f&uuml;r das Bild, das die Logos der Kreditkarten enth&auml;lt.<a href="https://guides.wirecard.at/request_parameters#backgroundcolor" target="_blank">Weitere Informationen: backgroundColor</a>',
    'SHOP_MODULE_bWcpUseLayout' => 'Endger&auml;t-Optimierung',
    'HELP_SHOP_MODULE_bWcpUseLayout' => 'Weiterleitung des Endger&auml;tes, f&uuml;r das Wirecard Checkout Page optimiert werden soll ("desktop", "tablet" oder "smartphone"; Parameter <a href="https://guides.wirecard.at/doku.php/request_parameters#layout" target="_blank">layout</a>). Bitte kontaktieren Sie unsere <a href="https://guides.wirecard.at/sales" target="_blank">Sales-Teams</a> um dieses Feature freizuschalten.<br /><a href="https://guides.wirecard.at/request_parameters#layout" target="_blank">Weitere Informationen: layout</a>',
    'SHOP_MODULE_sWcpPluginMode' => 'Plugin Modus',
    'SHOP_MODULE_sWcpPluginMode_Demo' => 'Demo',
    'SHOP_MODULE_sWcpPluginMode_Test' => 'Test',
    'SHOP_MODULE_sWcpPluginMode_Live' => 'Production',
    'HELP_SHOP_MODULE_sWcpPluginMode' => 'Zum Testen der Integration eine vordefinierte Konfiguration ausw&auml;hlen. F&uuml;r Produktivsysteme "Production" ausw&auml;hlen.',
    'SHOP_MODULE_bWcpDuplicateRequestCheck' => '&Uuml;berpr&uuml;fung auf doppelte Anfragen',
    'HELP_SHOP_MODULE_bWcpDuplicateRequestCheck' => '&Uuml;berpr&uuml;fung auf mehrfache Anfragen seitens Ihres Kunden.<a href="https://guides.wirecard.at/request_parameters#duplicaterequestcheck" target="_blank">Weitere Informationen: duplicateRequestCheck</a>',
    'SHOP_MODULE_sWcpConfirmMail' => 'Benachrichtigungsmail',
    'HELP_SHOP_MODULE_sWcpConfirmMail' => 'Benachrichtigung per E-Mail &uuml;ber Zahlungen Ihrer Kunden, falls ein Kommunikationsproblem zwischen Wirecard und Ihrem Onlineshop aufgetreten ist. Bitte kontaktieren Sie unsere <a href="https://guides.wirecard.at/sales" target="_blank">Sales-Teams</a> um dieses Feature freizuschalten.<br /><a href="https://guides.wirecard.at/request_parameters#confirmmail" target="_blank">Weitere Informationen: confirmMail</a>',
    'SHOP_MODULE_sWcpMaxRetries' => 'Max. Versuche',
    'HELP_SHOP_MODULE_sWcpMaxRetries' => 'Definition der max. m&ouml;glichen Bezahlversuche eines bestimmten Auftrags. Bitte kontaktieren Sie unsere <a href="https://guides.wirecard.at/sales" target="_blank">Sales-Teams</a> um dieses Feature freizuschalten.<br /><a href="https://guides.wirecard.at/request_parameters#maxretries" target="_blank">Weitere Informationen: maxRetries</a>',
    'SHOP_MODULE_sWcpPaymentTypeSortOrder' => 'Reihenfolge der Zahlungsmittel',
    'HELP_SHOP_MODULE_sWcpPaymentTypeSortOrder' => 'Reihenfolge der angezeigten Zahlungsmittel im Auswahlbereich. Bitte kontaktieren Sie unsere <a href="https://guides.wirecard.at/sales" target="_blank">Sales-Teams</a> um dieses Feature freizuschalten.<br /><a href="https://guides.wirecard.at/request_parameters#paymenttypesortorder" target="_blank">Weitere Informationen: paymenttypeSortOrder</a>',
    'SHOP_MODULE_GROUP_wcp_settings' => 'Plugin-Einstellungen',
    'SHOP_MODULE_bWcpSendAdditionalCustomerData' => 'Verrechnungsdaten des Konsumenten mitsenden',
    'HELP_SHOP_MODULE_bWcpSendAdditionalCustomerData' => 'Weiterleitung der Rechnungs- und Versanddaten des Kunden an den Finanzdienstleister.',
    'SHOP_MODULE_bWcpSendAdditionalBasketData' => 'Warenkorbdaten mitsenden',
    'HELP_SHOP_MODULE_bWcpSendAdditionalBasketData' => 'Weiterleitung des Warenkorbs des Kunden an den Finanzdienstleister.',
    'SHOP_MODULE_bWcpLogConfirmations' => 'Speichern der Bezahlprozess-Ergebnisse',
    'HELP_SHOP_MODULE_bWcpLogConfirmations' => 'Speichern aller Ergebnisse des Bezahlprozesses, d.h. jedes Aufrufs des Wirecard Checkout Servers der Best&auml;tigungs-URL.',
    'SHOP_MODULE_sWcpCheckoutFolder' => 'Ordner f&uuml;r neue Bestellungen',
    'HELP_SHOP_MODULE_sWcpCheckoutFolder' => 'Auswahl des Ordners, in den neue Bestellungen gespeichert werden.',
    'SHOP_MODULE_sWcpCheckoutFolder_ORDERFOLDER_NEW' => 'Neu',
    'SHOP_MODULE_sWcpCheckoutFolder_ORDERFOLDER_FINISHED' => 'Bearbeitet',
    'SHOP_MODULE_sWcpCheckoutFolder_ORDERFOLDER_PROBLEMS' => 'Probleme',
    'SHOP_MODULE_sWcpSuccessFolder' => 'Ordner f&uuml;r erfolgreiche Zahlungen',
    'HELP_SHOP_MODULE_sWcpSuccessFolder' => 'Auswahl des Ordners, in den erfolgreich bezahlte Bestellungen gespeichert werden.',
    'SHOP_MODULE_sWcpSuccessFolder_ORDERFOLDER_NEW' => 'Neu',
    'SHOP_MODULE_sWcpSuccessFolder_ORDERFOLDER_FINISHED' => 'Bearbeitet',
    'SHOP_MODULE_sWcpSuccessFolder_ORDERFOLDER_PROBLEMS' => 'Probleme',
    'SHOP_MODULE_sWcpPendingFolder' => 'Ordner f&uuml;r ausstehende Zahlungen',
    'HELP_SHOP_MODULE_sWcpPendingFolder' => 'Auswahl des Ordners, in den Bestellungen mit ausstehendem Zahlungsstatus gespeichert werden.',
    'SHOP_MODULE_sWcpPendingFolder_ORDERFOLDER_NEW' => 'Neu',
    'SHOP_MODULE_sWcpPendingFolder_ORDERFOLDER_FINISHED' => 'Bearbeitet',
    'SHOP_MODULE_sWcpPendingFolder_ORDERFOLDER_PROBLEMS' => 'Probleme',
    'SHOP_MODULE_sWcpCancelFolder' => 'Ordner f&uuml;r abgebrochene Zahlungen',
    'HELP_SHOP_MODULE_sWcpCancelFolder' => 'Auswahl des Ordners, in den Bestellungen mit abgebrochenem Bezahlprozess gespeichert werden.',
    'SHOP_MODULE_sWcpCancelFolder_ORDERFOLDER_NEW' => 'Neu',
    'SHOP_MODULE_sWcpCancelFolder_ORDERFOLDER_FINISHED' => 'Bearbeitet',
    'SHOP_MODULE_sWcpCancelFolder_ORDERFOLDER_PROBLEMS' => 'Probleme',
    'SHOP_MODULE_sWcpFailureFolder' => 'Ordner f&uuml;r fehlerhafte Zahlungen',
    'HELP_SHOP_MODULE_sWcpFailureFolder' => 'Auswahl des Ordners, in den Bestellungen mit fehlerhaftem Bezahlprozess gespeichert werden.',
    'SHOP_MODULE_sWcpFailureFolder_ORDERFOLDER_NEW' => 'Neu',
    'SHOP_MODULE_sWcpFailureFolder_ORDERFOLDER_FINISHED' => 'Bearbeitet',
    'SHOP_MODULE_sWcpFailureFolder_ORDERFOLDER_PROBLEMS' => 'Probleme',
    'SHOP_MODULE_bWcpDisableDeviceDetection' => 'iFrame f&uuml;r mobile Ger&auml;te deaktivieren',
    'HELP_SHOP_MODULE_bWcpDisableDeviceDetection' => 'Bei Verwendung eines mobilen Ger&auml;tes wird der Konsument zur Wirecard Checkout Page weitergeleitet, d.h. diese wird in diesem Fall nicht im iFrame angezeigt.',
    'SHOP_MODULE_sWcpShopName' => 'Shop-Pr&auml;fix auf Buchungstext',
    'HELP_SHOP_MODULE_sWcpShopName' => 'Referenz zu Ihrem Onlineshop im Buchungstext f&uuml;r Ihren Kunden, max. 9 Zeichen (wird zusammen mit der Auftragsnummer zum Erstellen des Parameters <a href="https://guides.wirecard.at/request_parameters#customerstatement" target="_blank">customerStatement</a> verwendet.)',
    'SHOP_MODULE_GROUP_wcp_iframe_settings' => 'iFrame-Einstellungen',
    'SHOP_MODULE_bWcp_bancontact_mistercash_UseIframe' => 'Bancontact - iFrame verwenden',
    'SHOP_MODULE_bWcp_ccard_UseIframe' => 'Kreditkarte iFrame verwenden',
    'SHOP_MODULE_bWcp_ccard-moto_UseIframe' => 'Kreditkarte Mail Order / Telephone Order - iFrame verwenden',
    'SHOP_MODULE_bWcp_masterpass_UseIframe' => 'masterpass - iFrame verwenden',
    'SHOP_MODULE_bWcp_ekonto_UseIframe' => 'eKonto - iFrame verwenden',
    'SHOP_MODULE_bWcp_epay_bg_UseIframe' => 'ePay.bg - iFrame verwenden',
    'SHOP_MODULE_bWcp_eps_UseIframe' => 'eps Online-&Uuml;berweisung - iFrame verwenden',
    'SHOP_MODULE_bWcp_giropay_UseIframe' => 'giropay - iFrame verwenden',
    'SHOP_MODULE_bWcp_idl_UseIframe' => 'iDEAL - iFrame verwenden',
    'SHOP_MODULE_bWcp_installment_UseIframe' => 'Kauf auf Raten - iFrame verwenden',
    'SHOP_MODULE_bWcp_invoice_b2b_UseIframe' => 'Kauf auf Rechnung B2B - iFrame verwenden',
    'SHOP_MODULE_bWcp_invoice_b2c_UseIframe' => 'Kauf auf Rechnung B2C - iFrame verwenden',
    'SHOP_MODULE_bWcp_maestro_UseIframe' => 'Maestro SecureCode - iFrame verwenden',
    'SHOP_MODULE_bWcp_moneta_UseIframe' => 'moneta.ru - iFrame verwenden',
    'SHOP_MODULE_bWcp_paypal_UseIframe' => 'PayPal - iFrame verwenden',
    'SHOP_MODULE_bWcp_pbx_UseIframe' => 'paybox - iFrame verwenden',
    'SHOP_MODULE_bWcp_poli_UseIframe' => 'POLi - iFrame verwenden',
    'SHOP_MODULE_bWcp_przelewy24_UseIframe' => 'Przelewy24 - iFrame verwenden',
    'SHOP_MODULE_bWcp_psc_UseIframe' => 'paysafecard - iFrame verwenden',
    'SHOP_MODULE_bWcp_quick_UseIframe' => '@Quick - iFrame verwenden',
    'SHOP_MODULE_bWcp_select_UseIframe' => 'Zahlungsmittelauswahl - iFrame verwenden',
    'SHOP_MODULE_bWcp_sepa-dd_UseIframe' => 'SEPA Lastschrift - iFrame verwenden',
    'SHOP_MODULE_bWcp_skrillwallet_UseIframe' => 'Skrill Digital Wallet - iFrame verwenden',
    'SHOP_MODULE_bWcp_sofortueberweisung_UseIframe' => 'Sofort. - iFrame verwenden',
    'SHOP_MODULE_bWcp_tatrapay_UseIframe' => 'TatraPay - iFrame verwenden',
    'SHOP_MODULE_bWcp_trustly_UseIframe' => 'Trustly - iFrame verwenden',
    'SHOP_MODULE_bWcp_voucher_UseIframe' => 'Mein Gutschein - iFrame verwenden',
    'SHOP_MODULE_bWcp_trustpay_UseIframe' => 'TrustPay - iFrame verwenden',
    'SHOP_MODULE_GROUP_wcp_installment_invoice_settings' => 'Einstellungen f&uuml;r Kauf auf Raten/Rechnung',
    'SHOP_MODULE_sWcpInstallmentProvider' => 'Provider f&uuml;r Kauf auf Raten',
    'SHOP_MODULE_sWcpInvoiceProvider' => 'Provider f&uuml;r Kauf auf Rechnung',
    'HELP_SHOP_MODULE_sWcpInvoiceProvider' => 'Auswahl des Providers f&uuml;r Kauf auf Rechnung. Eingabe wird ignoriert, wenn Kauf auf Rechnung nicht freigeschalten ist.',
    'HELP_SHOP_MODULE_sWcpInstallmentProvider' => 'Auswahl des Providers f&uuml;r Kauf auf Raten. Eingabe wird ignoriert, wenn Kauf auf Raten nicht freigeschalten ist.',
    'SHOP_MODULE_sWcpInvoiceProvider_RATEPAY' => 'Ratepay',
    'SHOP_MODULE_sWcpInvoiceProvider_WIRECARD' => 'Wirecard',
    'SHOP_MODULE_sWcpInvoiceProvider_PAYOLUTION' => 'payolution',
    'SHOP_MODULE_sWcpInstallmentProvider_RATEPAY' => 'Ratepay',
    'SHOP_MODULE_sWcpInstallmentProvider_PAYOLUTION' => 'payolution',
    'SHOP_MODULE_bWcpPayolutionAllowDifferingAddresses' => 'F&uuml;r payolution: Rechnungsadresse darf von der Lieferadresse abweichen.',
    'SHOP_MODULE_bWcpInvoiceb2bTrustedShopsCheckbox' => 'Anzeige der "Trusted Shops"-Checkbox f&uuml;r Kauf auf Rechnung B2B von payolution',
    'HELP_SHOP_MODULE_bWcpInvoiceb2bTrustedShopsCheckbox' => 'Anzeige der Checkbox mit den payolution-Bedingungen, die vom Kunden w&auml;hrend des Bezahlprozesses best&auml;tigt werden m&uuml;ssen, wenn Ihr Onlineshop als "Trusted Shop" zertifiziert ist.',
    'SHOP_MODULE_bWcpInvoiceb2cTrustedShopsCheckbox' => 'Anzeige der "Trusted Shops"-Checkbox f&uuml;r Kauf auf Rechnung B2C von payolution',
    'HELP_SHOP_MODULE_bWcpInvoiceb2cTrustedShopsCheckbox' => 'Anzeige der Checkbox mit den payolution-Bedingungen, die vom Kunden w&auml;hrend des Bezahlprozesses best&auml;tigt werden m&uuml;ssen, wenn Ihr Onlineshop als "Trusted Shop" zertifiziert ist.',
    'SHOP_MODULE_bWcpInstallmentTrustedShopsCheckbox' => 'Anzeige der "Trusted Shops"-Checkbox f&uuml;r Kauf auf Raten von payolution',
    'HELP_SHOP_MODULE_bWcpInstallmentTrustedShopsCheckbox' => 'Anzeige der Checkbox mit den payolution-Bedingungen, die vom Kunden w&auml;hrend des Bezahlprozesses best&auml;tigt werden m&uuml;ssen, wenn Ihr Onlineshop als "Trusted Shop" zertifiziert ist.',
    'SHOP_MODULE_sWcpPayolutionMId' => 'payolution mID',
    'HELP_SHOP_MODULE_sWcpPayolutionMId' => 'payolution-H&auml;ndler-ID, bestehend aus dem Base64-enkodierten Firmennamen, die f&uuml;r den Link "Einwilligen" gesetzt werden kann (https://www.base64encode.org/).',
    'WIRECARD_CHECKOUT_PAGE_BANCONTACT_MISTERCASH_LABEL' => 'Bancontact',
    'WIRECARD_CHECKOUT_PAGE_CCARD_LABEL' => 'Kreditkarte',
    'WIRECARD_CHECKOUT_PAGE_CCARD-MOTO_LABEL' => 'Kreditkarte Mail Order / Telephone Order',
    'WIRECARD_CHECKOUT_PAGE_EKONTO_LABEL' => 'eKonto',
    'WIRECARD_CHECKOUT_PAGE_EPAY_BG_LABEL' => 'ePay.bg',
    'WIRECARD_CHECKOUT_PAGE_EPS_LABEL' => 'eps Online-&Uuml;berweisung',
    'WIRECARD_CHECKOUT_PAGE_GIROPAY_LABEL' => 'giropay',
    'WIRECARD_CHECKOUT_PAGE_IDL_LABEL' => 'iDEAL',
    'WIRECARD_CHECKOUT_PAGE_INSTALLMENT_LABEL' => 'Kauf auf Raten',
    'WIRECARD_CHECKOUT_PAGE_INVOICE_B2B_LABEL' => 'Kauf auf Rechnung B2B',
    'WIRECARD_CHECKOUT_PAGE_INVOICE_B2C_LABEL' => 'Kauf auf Rechnung B2C',
    'WIRECARD_CHECKOUT_PAGE_MAESTRO_LABEL' => 'Maestro SecureCode',
    'WIRECARD_CHECKOUT_PAGE_MONETA_LABEL' => 'moneta.ru',
    'WIRECARD_CHECKOUT_PAGE_MASTERPASS_LABEL' => 'masterpass',
    'WIRECARD_CHECKOUT_PAGE_PAYPAL_LABEL' => 'PayPal',
    'WIRECARD_CHECKOUT_PAGE_PBX_LABEL' => 'paybox',
    'WIRECARD_CHECKOUT_PAGE_POLI_LABEL' => 'POLi',
    'WIRECARD_CHECKOUT_PAGE_PRZELEWY24_LABEL' => 'Przelewy24',
    'WIRECARD_CHECKOUT_PAGE_PSC_LABEL' => 'paysafecard',
    'WIRECARD_CHECKOUT_PAGE_QUICK_LABEL' => '@Quick',
    'WIRECARD_CHECKOUT_PAGE_SELECT_LABEL' => 'Zahlungsmittelauswahl',
    'WIRECARD_CHECKOUT_PAGE_SEPA-DD_LABEL' => 'SEPA Lastschrift',
    'WIRECARD_CHECKOUT_PAGE_SKRILLWALLET_LABEL' => 'Skrill Digital Wallet',
    'WIRECARD_CHECKOUT_PAGE_SOFORTUEBERWEISUNG_LABEL' => 'Sofort.',
    'WIRECARD_CHECKOUT_PAGE_TATRAPAY_LABEL' => 'TatraPay',
    'WIRECARD_CHECKOUT_PAGE_TRUSTLY_LABEL' => 'Trustly',
    'WIRECARD_CHECKOUT_PAGE_VOUCHER_LABEL' => 'Mein Gutschein',
    'WIRECARD_CHECKOUT_PAGE_TRUSTPAY_LABEL' => 'TrustPay',
    'WIRECARD_CHECKOUT_PAGE_BANCONTACT_MISTERCASH_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_CCARD_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_CCARD-MOTO_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_EKONTO_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_EPAY_BG_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_ELV_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_EPS_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_GIROPAY_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_IDL_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_INSTALLMENT_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_INVOICE_B2B_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_INVOICE_B2C_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_MAESTRO_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_MONETA_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_MASTERPASS_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_PAYPAL_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_PBX_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_POLI_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_PRZELEWY24_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_PSC_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_QUICK_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_SELECT_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_SEPA-DD_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_SKRILLWALLET_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_SOFORTUEBERWEISUNG_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_TATRAPAY_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_TRUSTLY_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_VOUCHER_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_TRUSTPAY_DESC' => '',
    'WIRECARD_CHECKOUT_PAGE_COMMUNICATION_ERROR' => 'Kommunikationsfehler zum Zahlungsdienstleister. Bitte versuchen Sie es sp&auml;ter erneut.',
    'WCP_SUBMIT' => 'Senden',
    'WCP_SUBMIT_CONFIG' => 'Wirecard Checkout Page Konfiguration senden.',
    'MODULE_PAYMENT_WCP_EXPORT_CONFIG_RECEIVER' => 'Empf&auml;nger',
    'MODULE_PAYMENT_WCP_EXPORT_CONFIG_CONFIG_STRING' => 'Konfiguration',
    'MODULE_PAYMENT_WCP_EXPORT_CONFIG_DESC_TEXT' => 'Kommentar',
    'MODULE_PAYMENT_WCP_EXPORT_CONFIG_RETURN_MAIL' => 'Antwort an E-Mail-Adresse',
    'ORDER_OVERVIEW_WCP_FINANCIALINSTITUTION' => 'financialInstitution',
    'ORDER_OVERVIEW_WCP_LANGUAGE' => 'language',
    'ORDER_OVERVIEW_WCP_ORDERNUMBER' => 'orderNumber',
    'ORDER_OVERVIEW_WCP_AMOUNT' => 'amount',
    'ORDER_OVERVIEW_WCP_CURRENCY' => 'currency',
    'ORDER_OVERVIEW_WCP_GATEWAYCONTRACTNUMBER' => 'gatewayContractNumber',
    'ORDER_OVERVIEW_WCP_GATEWAYREFERENCENUMBER' => 'gatewayReferenceNumber',
    'ORDER_OVERVIEW_WCP_MESSAGE' => 'message',
    'ORDER_OVERVIEW_WCP_LIABILITYSHIFTINDICATOR' => 'liabilityShiftIndicator',
    'ORDER_OVERVIEW_WCP_INSTRUMENTCOUNTRY' => 'instrumentCountry',
    'ORDER_OVERVIEW_WCP_RISKINTERCEPT' => 'riskIntercept',
    'ORDER_OVERVIEW_WCP_RISKREASONCODE' => 'riskReasonCode',
    'ORDER_OVERVIEW_WCP_RISKREASONMESSAGE' => 'riskReasonMessage',
    'ORDER_OVERVIEW_WCP_RISKRETURNCODE' => 'riskReturnCode',
    'ORDER_OVERVIEW_WCP_RISKRETURNMESSAGE' => 'riskReturnMessage',
    'ORDER_OVERVIEW_WCP_PAYMENTSTATE' => 'paymentState',
    'ORDER_OVERVIEW_WCP_LANG' => 'lang',
    'ORDER_OVERVIEW_WCP_LANGID' => 'langId',
    'ORDER_OVERVIEW_WCP_CHECKOUTTYPE' => 'checkoutType',
    'ORDER_OVERVIEW_WCP_AVSRESPONSECODE' => 'avsResponseCode',
    'ORDER_OVERVIEW_WCP_AVSRESPONSEMESSAGE' => 'avsResponseMessage',
    'ORDER_OVERVIEW_WCP_AVSPROVIDERRESULTCODE' => 'avsProviderResultCode',
    'ORDER_OVERVIEW_WCP_AVSPROVIDERRESULTMESSAGE' => 'avsProviderResultMessage',
    'ORDER_OVERVIEW_WCP_IDEALCONSUMERNAME' => 'idealConsumerName',
    'ORDER_OVERVIEW_WCP_IDEALCONSUMERBIC' => 'idealConsumerBIC',
    'ORDER_OVERVIEW_WCP_IDEALCONSUMERCITY' => 'idealConsumerCity',
    'ORDER_OVERVIEW_WCP_IDEALCONSUMERIBAN' => 'idealConsumerIBAN',
    'ORDER_OVERVIEW_WCP_IDEALCONSUMERACCOUNTNUMBER' => 'idealConsumerAccountNumber',
    'ORDER_OVERVIEW_WCP_SENDERACCOUNTOWNER' => 'senderAccountOwner',
    'ORDER_OVERVIEW_WCP_SENDERACCOUNTNUMBER' => 'senderAccountNumber',
    'ORDER_OVERVIEW_WCP_SENDERBANKNUMBER' => 'senderBankNumber',
    'ORDER_OVERVIEW_WCP_SENDERBANKNAME' => 'senderBankName',
    'ORDER_OVERVIEW_WCP_SENDERBIC' => 'senderBIC',
    'ORDER_OVERVIEW_WCP_SENDERIBAN' => 'senderIBAN',
    'ORDER_OVERVIEW_WCP_SENDERCOUNTRY' => 'senderCountry',
    'ORDER_OVERVIEW_WCP_SECURITYCRITERIA' => 'securityCriteria',
    'ORDER_OVERVIEW_WCP_AUTHENTICATED' => ' authenticated ',
    'ORDER_OVERVIEW_WCP_ANONYMOUSPAN' => 'anonymousPan',
    'ORDER_OVERVIEW_WCP_EXPIRY' => 'expiry',
    'ORDER_OVERVIEW_WCP_CARDHOLDER' => 'cardholder',
    'ORDER_OVERVIEW_WCP_MASKEDPAN' => 'maskedPan',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERID' => 'paypalPayerID',
    'ORDER_OVERVIEW_WCP_PAYPALPAYEREMAIL' => 'paypalPayerEmail',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERLASTNAME' => 'paypalPayerLastName',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERFIRSTNAME' => 'paypalPayerFirstName',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSNAME' => 'paypalPayerAddressName',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSCOUNTRY' => 'paypalPayerAddressCountry',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSCOUNTRYCODE' => 'paypalPayerAddressCountryCode',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSCITY' => 'paypalPayerAddressCity',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSSTATE' => 'paypalPayerAddressState',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSSTREET1' => 'paypalPayerAddressStreet1',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSSTREET2' => 'paypalPayerAddressStreet2',
    'ORDER_OVERVIEW_WCP_PAYPALPAYERADDRESSZIP' => 'paypalPayerAddressZIP',
    'ORDER_OVERVIEW_WCP_MANDATEID' => 'mandateId',
    'ORDER_OVERVIEW_WCP_MANDATESIGNATUREDATE' => 'mandateSignatureDate',
    'ORDER_OVERVIEW_WCP_CREDITORID' => 'creditorId',
    'ORDER_OVERVIEW_WCP_DUEDATE' => 'dueDate',
);
