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
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array (
        'id' => 'wirecardcheckoutpage',
        'title' => 'Wirecard Checkout Page',
        'description' => array(
            'de' => 'Wirecard Checkout Page Extension zur Bezahlung f&uuml;r Oxid eShop.<br /><br /><div id="helpPanel"><div class="bd"><strong>Support und Vertrieb:</strong><br /><a href="https://guides.wirecard.at/support" target="_blank">Support</a><br /><a href="https://guides.wirecard.at/sales" target="_blank">Sales</a></div></div>',
            'en' => 'Wirecard Checkout Page Payment Extension for Oxid eShop.<br /><br /><div id="helpPanel"><div class="bd"><strong>Support and sales information</strong><br /><a href="https://guides.wirecard.at/support" target="_blank">support</a><br /><a href="https://guides.wirecard.at/sales" target="_blank">sales</a></div></div>',
        ),
        'thumbnail' => 'picture.jpg',
        'version' => '2.6.3',
        'author' => 'Wirecard CEE',
        'email' => 'support@wirecard.at',
        'url' => 'http://www.wirecard.at',
        'extend' => array (
                'order'         => 'wirecard/checkoutpage/application/controllers/wcp_order',
                'payment'         => 'wirecard/checkoutpage/application/controllers/wcp_payment',
                'thankyou'      => 'wirecard/checkoutpage/application/controllers/wcp_thankyou',
                'oxorder'       => 'wirecard/checkoutpage/application/models/wcp_oxorder',
                'oxemail'       => 'wirecard/checkoutpage/application/models/wcp_oxemail',
                'oxpaymentlist' => 'wirecard/checkoutpage/application/models/wcp_oxpaymentlist',
				'oxuserpayment'    => 'wirecard/checkoutpage/application/models/wcp_oxuserpayment',
		),
        'files' => array (
                'out/lang/de/wcp_lang.php'    => 'wirecard/checkoutpage/out/lang/de/wcp_lang.php',
                'out/lang/en/wcp_lang.php'    => 'wirecard/checkoutpage/out/lang/en/wcp_lang.php',
                'wdceepayment'                => 'wirecard/checkoutpage/application/controllers/wdceepayment.php',
                'wcp_submit_config'           => 'wirecard/checkoutpage/application/controllers/admin/wcp_submit_config.php',
                'wcp_OrderDbGateway'          => 'wirecard/checkoutpage/application/models/dbgateways/wcp_orderdbgateway.php',
                'WirecardCEE_MobileDetect'    => 'wirecard/checkoutpage/vendor/wccee_mobile_detect.php',
                'wirecardCheckoutPageEvents'  => 'wirecard/checkoutpage/core/wirecardcheckoutpageevents.php',
                'wcp_oxbasket'                => 'wirecard/checkoutpage/application/models/wcp_oxbasket.php',
				),
        'blocks' => array(
                  array(
                    'template' => 'page/checkout/payment.tpl',
                    'block'    => 'select_payment',
                    'file'     => '/application/views/blocks/wcp_payment_selector.tpl'
                  ),
                  array('template' => 'page/checkout/payment.tpl',
                      'block' => 'checkout_payment_errors',
                      'file' => '/application/views/blocks/wcp_checkout_errors.tpl'
                  ),
                  array('template' => 'page/checkout/order.tpl',
                      'block' => 'shippingAndPayment',
                      'file' => '/application/views/blocks/wcp_order.tpl'),

				array('template' => 'email/html/order_cust.tpl', 'block' => 'email_html_order_cust_paymentinfo_top', 'file' => '/application/views/blocks/email/html/order_cust.tpl'),
				array('template' => 'email/plain/order_cust.tpl', 'block' => 'email_plain_order_cust_paymentinfo', 'file' => '/application/views/blocks/email/plain/order_cust.tpl'),
       ),
        'events' => array(
                'onActivate'   => 'wirecardCheckoutPageEvents::onActivate',
                'onDeactivate' => 'wirecardCheckoutPageEvents::onDeactivate'
        ),

        'templates' => array (
                'page/checkout/wcp_checkout_iframe.tpl'   => 'wirecard/checkoutpage/out/tpl/page/checkout/wcp_checkout_iframe.tpl',
                'page/checkout/wcp_checkout_page.tpl'     => 'wirecard/checkoutpage/out/tpl/page/checkout/wcp_checkout_page.tpl',
                'page/checkout/wcp_return_iframe.tpl'     => 'wirecard/checkoutpage/out/tpl/page/checkout/wcp_return_iframe.tpl',
                'email/html/wcpDoublePaymentForOrder.tpl' => 'wirecard/checkoutpage/out/tpl/email/html/wcpDoublePaymentForOrder.tpl',
                'wcp_submit_config.tpl'                   => 'wirecard/checkoutpage/application/views/admin/tpl/wcp_submit_config.tpl',
        ),
        'settings' => array(
            array('group' => 'wcp_parameters', 'name' => 'sWcpPluginMode', 'type' => 'select', 'value' => '1', 'constraints' => 'Demo|Test|Live'),
            array('group' => 'wcp_parameters', 'name' => 'sWcpCustomerId', 'type' => 'str', 'value' => 'D200001'),
            array('group' => 'wcp_parameters', 'name' => 'sWcpShopId', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpSecret', 'type' => 'str', 'value' => 'B8AKTPWBRMNBV455FG6M2DANE99WU2'),
            array('group' => 'wcp_parameters', 'name' => 'sWcpImageUrl', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpServiceUrl', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpDisplayText', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpMaxRetries', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'bWcpAutoDeposit', 'type' => 'bool', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpBackgroundColor', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'bWcpAutoDeposit', 'type' => 'bool', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'bWcpDuplicateRequestCheck', 'type' => 'bool', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpConfirmMail', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpMaxRetries', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_parameters', 'name' => 'sWcpPaymentTypeSortOrder', 'type' => 'str', 'value' => ''),

            array('group' => 'wcp_settings', 'name' => 'bWcpSendAdditionalCustomerData', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_settings', 'name' => 'bWcpSendAdditionalBasketData', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_settings', 'name' => 'bWcpLogConfirmations', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_settings', 'name' => 'sWcpCheckoutFolder', 'type' => 'select', 'value' => 'ORDERFOLDER_NEW', 'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'),
            array('group' => 'wcp_settings', 'name' => 'sWcpSuccessFolder', 'type' => 'select',  'value' => 'ORDERFOLDER_NEW', 'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'),
            array('group' => 'wcp_settings', 'name' => 'sWcpPendingFolder', 'type' => 'select',  'value' => 'ORDERFOLDER_NEW', 'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'),
            array('group' => 'wcp_settings', 'name' => 'sWcpCancelFolder', 'type' => 'select',  'value' => 'ORDERFOLDER_NEW', 'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'),
            array('group' => 'wcp_settings', 'name' => 'sWcpFailureFolder', 'type' => 'select',  'value' => 'ORDERFOLDER_NEW', 'constraints' => 'ORDERFOLDER_NEW|ORDERFOLDER_FINISHED|ORDERFOLDER_PROBLEMS'),
            array('group' => 'wcp_settings', 'name' => 'bWcpUseLayout', 'type' => 'bool', 'value' => ''),
            array('group' => 'wcp_settings', 'name' => 'bWcpDisableDeviceDetection', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_settings', 'name' => 'sWcpShopName', 'type' => 'str', 'value' => 'Web Shop'),


            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_bancontact_mistercash_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_ccard_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_ccard-moto_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_ekonto_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_epay_bg_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_eps_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_giropay_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_idl_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_installment_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_invoice_b2b_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_invoice_b2c_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_maestro_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_moneta_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_mpass_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_paypal_UseIframe', 'type' => 'bool', 'value' => '0'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_pbx_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_poli_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_przelewy24_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_psc_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_quick_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_select_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_sepa-dd_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_skrilldirect_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_skrillwallet_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_sofortueberweisung_UseIframe', 'type' => 'bool', 'value' => '0'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_tatrapay_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_trustly_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_voucher_UseIframe', 'type' => 'bool', 'value' => '1'),
            array('group' => 'wcp_iframe_settings', 'name' => 'bWcp_trustpay_UseIframe', 'type' => 'bool', 'value' => '1'),

            array('group' => 'wcp_installment_invoice_settings', 'name' => 'sWcpInvoiceInstallmentProvider', 'type' => 'select', 'value' => 'PAYOLUTION', 'constraints' => 'PAYOLUTION|RATEPAY|WIRECARD'),
            array('group' => 'wcp_installment_invoice_settings', 'name' => 'sWcpPayolutionMId', 'type' => 'str', 'value' => ''),
            array('group' => 'wcp_installment_invoice_settings', 'name' => 'bWcpInstallmentTrustedShopsCheckbox', 'type' => 'bool', 'value' => ''),
            array('group' => 'wcp_installment_invoice_settings', 'name' => 'bWcpInvoiceb2bTrustedShopsCheckbox', 'type' => 'bool', 'value' => ''),
            array('group' => 'wcp_installment_invoice_settings', 'name' => 'bWcpInvoiceb2cTrustedShopsCheckbox', 'type' => 'bool', 'value' => ''),
			array('group' => 'wcp_installment_invoice_settings', 'name' => 'bWcpPayolutionAllowDifferingAddresses', 'type' => 'bool', 'value' => ''),
        ),
);



