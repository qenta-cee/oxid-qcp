[{assign var="payment" value=$oView->getPayment()}]

[{if $oView->isWcpPaymethod($payment->oxpayments__oxid->value)}]
    <div id="orderShipping">
        <form action="[{$oViewConf->getSslSelfLink()}]" method="post">
            <h3 class="section">
                <strong>[{oxmultilang ident="SHIPPING_CARRIER"}]</strong>
                [{$oViewConf->getHiddenSid()}]
                <input type="hidden" name="cl" value="payment">
                <input type="hidden" name="fnc" value="">
                <button type="submit" class="submitButton largeButton">[{oxmultilang ident="EDIT"}]</button>
            </h3>
        </form>
        [{assign var="oShipSet" value=$oView->getShipSet()}]
        [{$oShipSet->oxdeliveryset__oxtitle->value}]
    </div>
    <div id="orderPayment">
        <form action="[{$oViewConf->getSslSelfLink()}]" method="post">
            <h3 class="section">
                <strong>[{oxmultilang ident="PAYMENT_METHOD"}]</strong>
                [{$oViewConf->getHiddenSid()}]
                <input type="hidden" name="cl" value="payment">
                <input type="hidden" name="fnc" value="">
                <button type="submit" class="submitButton largeButton">[{oxmultilang ident="EDIT"}]</button>
            </h3>
        </form>

        [{$oView->getWcpRawPaymentDesc($payment->oxpayments__oxdesc->value)}]
    </div>
    [{else}]
    [{$smarty.block.parent}]
    [{/if}]