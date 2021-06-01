<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/shop_plugins:info
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'qentacheckoutpage',
    'title' => 'QENTA Checkout Page',
    'description' => array(
        'de' => 'QENTA Checkout Page Extension zur Bezahlung f&uuml;r Oxid eShop.<br /><br /><div id="helpPanel"><div class="bd"><strong>Support und Vertrieb:</strong><br /><a href="https://guides.qenta.com/support" target="_blank">Support</a><br /><a href="https://guides.qenta.com/sales" target="_blank">Sales</a></div></div>',
        'en' => 'QENTA Checkout Page Payment Extension for Oxid eShop.<br /><br /><div id="helpPanel"><div class="bd"><strong>Support and sales information</strong><br /><a href="https://guides.qenta.com/support" target="_blank">support</a><br /><a href="https://guides.qenta.com/sales" target="_blank">sales</a></div></div>',
    ),
    'thumbnail' => 'qenta.svg',
    'version' => '3.0.0',
    'author' => 'QENTA Payment CEE GmbH',
    'email' => 'support@qenta.com',
    'url' => 'https://www.qenta-cee.com/',
    'extend' => array(
        'order' => 'qenta/checkoutpage/application/controllers/qcp_order',
        'payment' => 'qenta/checkoutpage/application/controllers/qcp_payment',
        'thankyou' => 'qenta/checkoutpage/application/controllers/qcp_thankyou',
        'oxorder' => 'qenta/checkoutpage/application/models/qcp_oxorder',
        'oxemail' => 'qenta/checkoutpage/application/models/qcp_oxemail',
        'oxpaymentlist' => 'qenta/checkoutpage/application/models/qcp_oxpaymentlist',
        'oxuserpayment' => 'qenta/checkoutpage/application/models/qcp_oxuserpayment',
    ),
    'files' => array(
        'out/lang/de/qcp_lang.php' => 'qenta/checkoutpage/out/lang/de/qcp_lang.php',
        'out/lang/en/qcp_lang.php' => 'qenta/checkoutpage/out/lang/en/qcp_lang.php',
        'qentapayment' => 'qenta/checkoutpage/application/controllers/qentaeepayment.php',
        'qcp_submit_config' => 'qenta/checkoutpage/application/controllers/admin/qcp_submit_config.php',
        'qcp_OrderDbGateway' => 'qenta/checkoutpage/application/models/dbgateways/qcp_orderdbgateway.php',
        'WirecardCEE_MobileDetect' => 'qenta/checkoutpage/vendor/wccee_mobile_detect.php',
        'qentaCheckoutPageEvents' => 'qenta/checkoutpage/core/qentacheckoutpageevents.php',
        'qcp_oxbasket' => 'qenta/checkoutpage/application/models/qcp_oxbasket.php',
    ),
    'blocks' => array(
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => '/application/views/blocks/qcp_payment_selector.tpl'
        ),
        array(
            'template' => 'page/checkout/thankyou.tpl',
            'block' => 'checkout_thankyou_info',
            'file' => '/application/views/blocks/qcp_thankyou.tpl'
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'checkout_payment_errors',
            'file' => '/application/views/blocks/qcp_checkout_errors.tpl'
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'shippingAndPayment',
            'file' => '/application/views/blocks/qcp_order.tpl'
        ),

        array(
            'template' => 'email/html/order_cust.tpl',
            'block' => 'email_html_order_cust_paymentinfo_top',
            'file' => '/application/views/blocks/email/html/order_cust.tpl'
        ),
        array(
            'template' => 'email/plain/order_cust.tpl',
            'block' => 'email_plain_order_cust_paymentinfo',
            'file' => '/application/views/blocks/email/plain/order_cust.tpl'
        ),
    ),
    'events' => array(
        'onActivate' => 'qentaCheckoutPageEvents::onActivate',
        'onDeactivate' => 'qentaCheckoutPageEvents::onDeactivate'
    ),

    'templates' => array(
        'page/checkout/qcp_checkout_iframe.tpl' => 'qenta/checkoutpage/out/tpl/page/checkout/qcp_checkout_iframe.tpl',
        'page/checkout/qcp_checkout_page.tpl' => 'qenta/checkoutpage/out/tpl/page/checkout/qcp_checkout_page.tpl',
        'page/checkout/qcp_return_iframe.tpl' => 'qenta/checkoutpage/out/tpl/page/checkout/qcp_return_iframe.tpl',
        'email/html/qcpDoublePaymentForOrder.tpl' => 'qenta/checkoutpage/out/tpl/email/html/qcpDoublePaymentForOrder.tpl',
        'qcp_submit_config.tpl' => 'qenta/checkoutpage/application/views/admin/tpl/qcp_submit_config.tpl',
    ),
    'settings' => array(
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpPluginMode',
            'type' => 'select',
            'value' => '1',
            'constraints' => 'Demo|Test|Live'
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpCustomerId',
            'type' => 'str',
            'value' => 'D200001'
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpShopId',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpSecret',
            'type' => 'str',
            'value' => 'B8AKTPWBRMNBV455FG6M2DANE99WU2'
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpImageUrl',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpServiceUrl',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpDisplayText',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpMaxRetries',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'bQcpAutoDeposit',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpBackgroundColor',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'bQcpAutoDeposit',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'bQcpDuplicateRequestCheck',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpConfirmMail',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpMaxRetries',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_parameters',
            'name' => 'sQcpPaymentTypeSortOrder',
            'type' => 'str',
            'value' => ''
        ),

        array(
            'group' => 'qcp_settings',
            'name' => 'bQcpSendAdditionalCustomerData',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'bQcpSendAdditionalBasketData',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'bQcpLogConfirmations',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'sQcpCheckoutFolder',
            'type' => 'select',
            'value' => 'ORDERFOLDER_NEW',
            'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'sQcpSuccessFolder',
            'type' => 'select',
            'value' => 'ORDERFOLDER_NEW',
            'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'sQcpPendingFolder',
            'type' => 'select',
            'value' => 'ORDERFOLDER_NEW',
            'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'sQcpCancelFolder',
            'type' => 'select',
            'value' => 'ORDERFOLDER_NEW',
            'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'sQcpFailureFolder',
            'type' => 'select',
            'value' => 'ORDERFOLDER_NEW',
            'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'bQcpUseLayout',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'bQcpDisableDeviceDetection',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_settings',
            'name' => 'sQcpShopName',
            'type' => 'str',
            'value' => 'Web Shop'
        ),


        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_bancontact_mistercash_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_ccard_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_ccard-moto_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_masterpass_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_ekonto_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_epay_bg_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_eps_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_giropay_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_idl_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_installment_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_invoice_b2b_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_invoice_b2c_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_maestro_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_moneta_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_paypal_UseIframe',
            'type' => 'bool',
            'value' => '0'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_pbx_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_poli_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_przelewy24_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_psc_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_select_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_sepa-dd_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_skrillwallet_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_sofortueberweisung_UseIframe',
            'type' => 'bool',
            'value' => '0'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_tatrapay_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_trustly_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_voucher_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'qcp_iframe_settings',
            'name' => 'bQcp_trustpay_UseIframe',
            'type' => 'bool',
            'value' => '1'
        ),

        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'sQcpInvoiceProvider',
            'type' => 'select',
            'value' => 'QENTA',
            'constraints' => 'PAYOLUTION|RATEPAY|QENTA'
        ),
        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'sQcpInstallmentProvider',
            'type' => 'select',
            'value' => 'PAYOLUTION',
            'constraints' => 'PAYOLUTION|RATEPAY'
        ),
        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'sQcpPayolutionMId',
            'type' => 'str',
            'value' => ''
        ),
        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'bQcpInstallmentTrustedShopsCheckbox',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'bQcpInvoiceb2bTrustedShopsCheckbox',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'bQcpInvoiceb2cTrustedShopsCheckbox',
            'type' => 'bool',
            'value' => ''
        ),
        array(
            'group' => 'qcp_installment_invoice_settings',
            'name' => 'bQcpPayolutionAllowDifferingAddresses',
            'type' => 'bool',
            'value' => ''
        ),
    ),
);



