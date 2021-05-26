[{ assign var="shop"      value=$oEmailView->getShop() }]
[{ assign var="oViewConf" value=$oEmailView->getViewConfig() }]


[{include file="email/html/header.tpl" title=$shop->oxshops__oxname->value}]

    [{block name="email_html_qcpPaid_infoheader"}]
        <h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
            THERE ARE TWO PAYMENTS FOR AN ORDER
        </h3>
    [{/block}]

    [{block name="email_html_qcpPaid_oxordernr"}]
        <h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
            Oxid Ordernumber: [{ $orderNumber }]
        </h3>
    [{/block}]

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr valign="top">
        <td style="padding: 5px; border-bottom: 1px solid #ddd;" align="right">
            <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0;">
                orderNumbers:
            </p>
        </td>
        <td style="padding: 5px; border-bottom: 1px solid #ddd;">
            <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0;">
                [{ $qcpOrderNumber1 }]
            </p>
            <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0;">
                [{ $qcpOrderNumber2 }]
            </p>
        </td>
      </tr>
    </table>

    [{block name="email_html_qcpPaid_infofooter"}]
        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            [{ oxmultilang ident="EMAIL_QCP_PAID_HTML_YUORTEAM1" }] [{ $shop->oxshops__oxname->value }] [{ oxmultilang ident="EMAIL_QCP_PAID_HTML_YUORTEAM2" }]
        </p>
    [{/block}]

    [{block name="email_html_sendednow_ts"}]
        [{if $oViewConf->showTs("ORDERCONFEMAIL") && $oViewConf->getTsId() }]
            <h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
                [{ oxmultilang ident="EMAIL_QCP_PAID_HTML_TS_RATINGS_RATEUS" }]
            </h3>

            <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                <a href="[{ $oViewConf->getTsRatingUrl() }]" target="_blank" title="[{ oxmultilang ident="TS_RATINGS_URL_TITLE" }]">
                    <img src="https://www.trustedshops.com/bewertung/widget/img/bewerten_de.gif" border="0" alt="[{ oxmultilang ident="TS_RATINGS_BUTTON_ALT" }]" align="middle">
                </a>
            </p>
        [{/if}]
    [{/block}]

[{include file="email/html/footer.tpl"}]
