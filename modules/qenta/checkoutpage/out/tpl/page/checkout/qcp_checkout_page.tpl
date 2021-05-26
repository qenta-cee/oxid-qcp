<!DOCTYPE HTML>
<html>
    <head>
        <title>Processing Payment...</title>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15">
        <style type="text/css">
            html { font: 12px / 140% arial, helvetica, clean, sans-serif; }
        </style>
    </head>
    <body onLoad="document.form.submit();">
        <center><h3>[{ oxmultilang ident="qcp_payment_page_redirect" }]</h3></center>
        <form method="post" name="form" action="[{$qcpPaymentUrl}]">
        [{foreach from=$qcpRequest key=name  item=value}]
            <input type="hidden" name="[{$name}]" value="[{$value}]">
        [{/foreach}]
        </form>
    </body>
</html>