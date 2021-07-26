[{if isset( $dobData.month ) }]
    [{assign var="iBirthdayMonth" value=$dobData.month }]
[{else}]
    [{assign var="iBirthdayMonth" value=0}]
[{/if}]
[{if isset( $dobData.day ) }]
    [{assign var="iBirthdayDay" value=$dobData.day}]
[{else}]
    [{assign var="iBirthdayDay" value=0}]
[{/if}]
[{if isset( $dobData.year ) }]
    [{assign var="iBirthdayYear" value=$dobData.year }]
[{else}]
    [{assign var="iBirthdayYear" value=0}]
[{/if}]

[{if !isset($qcsPaymentCount)}]
    [{$oView->getQcpRatePayConsumerDeviceId()}]
    [{ assign var="qcpPaymentCount" value="1"}]
[{/if}]

[{if $oView->isQcpPaymethod($sPaymentID)}]
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]">[{$oView->getQcpPaymentLogo($sPaymentID)}]<b>[{ $oView->getQcpRawPaymentDesc($paymentmethod->oxpayments__oxdesc->value)}]</b>
                [{if $paymentmethod->getPrice()}]
                    [{assign var="oPaymentPrice" value=$paymentmethod->getPrice() }]
                    [{if $oViewConf->isFunctionalityEnabled('blShowVATForPayCharge') }]
                        ( [{oxprice price=$oPaymentPrice->getNettoPrice() currency=$currency}]

                    [{if $oPaymentPrice->getVatValue() > 0}]
                            [{ oxmultilang ident="PLUS_VAT" }] [{oxprice price=$oPaymentPrice->getVatValue() currency=$currency }]
                    [{/if}])
                    [{else}]
                        ([{oxprice price=$oPaymentPrice->getBruttoPrice() currency=$currency}])
                    [{/if}]
                [{/if}]

                </b></label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            [{assign var="aDynValues" value=$paymentmethod->getDynValues()}]
            [{if $aDynValues}]
            <ul>
                [{foreach from=$aDynValues item=value name=PaymentDynValues}]
                <li>
                    <label>[{$oView->getQcpPaymentLogo($paymentmethod)}] [{ $value->name}]</label>
                    <input id="[{$sPaymentID}]_[{$smarty.foreach.PaymentDynValues.iteration}]" type="text" class="textbox" size="20" maxlength="64" name="dynvalue[[{$value->name}]]" value="[{ $value->value}]">
                </li>
                [{/foreach}]
            </ul>
            [{/if}]

            [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value|trim}]
                <div class="desc">
                    [{ $paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                </div>
            [{/if}]
            [{/block}]

            [{if $bShowDobField && $oView->hasQcpDobField($sPaymentID)}]
                <div class="desc" id="[{$sPaymentID}]_desc">

                <ul class="form clear">
                <li class="oxDate[{if $aErrors.oxuser__oxbirthdate}] oxInValid[{/if}]">
                    <label class="req">[{ oxmultilang ident="BIRTHDATE" suffix="COLON" }]</label>
                    <select class='oxMonth js-oxValidate js-oxValidate_date js-oxValidate_notEmpty' name='[{$sPaymentID}]_iBirthdayMonth'>
                        <option value="" >-</option>
                        [{section name="month" start=1 loop=13 }]
                        <option value="[{$smarty.section.month.index}]" [{if $iBirthdayMonth == $smarty.section.month.index}] selected="selected" [{/if}]>
                            [{oxmultilang ident="MONTH_NAME_"|cat:$smarty.section.month.index}]
                        </option>
                        [{/section}]
                    </select>
					<label class="innerLabel" for="[{$sPaymentID}]_oxDay" style="left: 250px; top: 5px;">[{ oxmultilang ident="DAY" }]</label>
                    <input id="[{$sPaymentID}]_oxDay" class='oxDay js-oxValidate' name='[{$sPaymentID}]_iBirthdayDay' type="text" data-fieldsize="xsmall" maxlength="2" value="[{if $iBirthdayDay > 0 }][{$iBirthdayDay }][{/if}]" />
                    [{oxscript include="js/widgets/oxinnerlabel.js" priority=10 }]
                    [{oxscript add="$( '#`$sPaymentID`_oxDay' ).oxInnerLabel({sReloadElement:'#payment'});"}]
					<label class="innerLabel" for="[{$sPaymentID}]_oxYear" style="left: 287px; top: 5px;">[{ oxmultilang ident="YEAR" }]</label>
                    <input id="[{$sPaymentID}]_oxYear" class='oxYear js-oxValidate' name='[{$sPaymentID}]_iBirthdayYear' type="text" data-fieldsize="small" maxlength="4" value="[{if $iBirthdayYear }][{$iBirthdayYear }][{/if}]" />
                    [{oxscript include="js/widgets/oxinnerlabel.js" priority=10 }]
                    [{oxscript add="$( '#`$sPaymentID`_oxYear' ).oxInnerLabel({sReloadElement:'#payment'});"}]
					<p class="oxValidateError">
                        <span class="js-oxError_notEmpty">[{ oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
                        <span class="js-oxError_incorrectDate">[{ oxmultilang ident="ERROR_MESSAGE_INCORRECT_DATE" }]</span>
                        [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxbirthdate}]
                    </p>
                </li>
                </ul>

            </div>
            [{/if}]

            [{if $sPaymentID=='qcp_eps'}]
            <div class="desc" id="[{$sPaymentID}]_desc">
                <ul class="form clear">
                    <select name="[{$sPaymentID}]_financialInstitution">
                        <option value="ARZ|AB">Apothekerbank</option>
                        <option value="ARZ|AAB">Austrian Anadi Bank AG</option>
                        <option value="ARZ|BAF">&Auml;rztebank</option>
                        <option value="BA-CA">Bank Austria</option>
                        <option value="ARZ|BCS">Bankhaus Carl Sp&auml;ngler & Co. AG</option>
                        <option value="ARZ|BSS">Bankhaus Schelhammer & Schattera AG</option>
                        <option value="Bawag|BG">BAWAG P.S.K. AG</option>
                        <option value="ARZ|BKS">BKS Bank AG</option>
                        <option value="ARZ|BKB">Br&uuml;ll Kallmus Bank AG</option>
                        <option value="ARZ|BTV">BTV VIER L&Auml;NDER BANK</option>
                        <option value="ARZ|CBGG">Capital Bank Grawe Gruppe AG</option>
                        <option value="ARZ|VB">Volksbank Gruppe</option>
                        <option value="ARZ|DB">Dolomitenbank</option>
                        <option value="Bawag|EB">Easybank AG</option>
                        <option value="Spardat|EBS">Erste Bank und Sparkassen</option>
                        <option value="ARZ|HAA">Hypo Alpe-Adria-Bank International AG</option>
                        <option value="ARZ|VLH">Hypo Landesbank Vorarlberg</option>
                        <option value="ARZ|HI">HYPO NOE Gruppe Bank AG</option>
                        <option value="ARZ|NLH">HYPO NOE Landesbank AG</option>
                        <option value="Hypo-Racon|O">Hypo Ober&ouml;sterreich</option>
                        <option value="Hypo-Racon|S">Hypo Salzburg</option>
                        <option value="Hypo-Racon|St">Hypo Steiermark</option>
                        <option value="ARZ|HTB">Hypo Tirol Bank AG</option>
                        <option value="BB-Racon">HYPO-BANK BURGENLAND Aktiengesellschaft</option>
                        <option value="ARZ|IB">Immo-Bank</option>
                        <option value="ARZ|OB">Oberbank AG</option>
                        <option value="Racon">Raiffeisen Bankengruppe &Ouml;sterreich</option>
                        <option value="ARZ|SB">Schoellerbank AG</option>
                        <option value="Bawag|SBW">Sparda Bank Wien</option>
                        <option value="ARZ|SBA">SPARDA-BANK AUSTRIA</option>
                        <option value="ARZ|VKB">Volkskreditbank AG</option>
                        <option value="ARZ|VRB">VR-Bank Braunau</option>
                    </select>
                </ul>
            </div>
            [{/if}]

            [{if $sPaymentID=='qcp_idl'}]
            <div class="desc" id="[{$sPaymentID}]_desc">
                <ul class="form clear">
                    <select name="[{$sPaymentID}]_financialInstitution">
                        <option value="ABNAMROBANK">ABN AMRO Bank</option>
                        <option value="ASNBANK">ASN Bank</option>
                        <option value="BUNQ">Bunq Bank</option>
                        <option value="INGBANK">ING</option>
                        <option value="KNAB">knab</option>
                        <option value="RABOBANK">Rabobank</option>
                        <option value="SNSBANK">SNS Bank</option>
                        <option value="REGIOBANK">RegioBank</option>
                        <option value="TRIODOSBANK">Triodos Bank</option>
                        <option value="VANLANSCHOT">Van Lanschot Bankiers</option>
                    </select>
                </ul>
            </div>
            [{/if}]


            [{if $oView->hasQcpVatIdField($sPaymentID) && $bShowVatIdField}]
            <div class="desc">
                <ul class="form clear">
                    <li [{if $aErrors.oxuser__oxustid}]class="oxInValid"[{/if}]>
                        <label class="req">[{ oxmultilang ident="VAT_ID_NUMBER" suffix="COLON" }]</label>
                        <input class="js-oxValidate" type="text" size="37" maxlength="255" name="sVatId" value="[{ $sVatId }]">
                        <p class="oxValidateError">
                            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxustid}]
                        </p>
                    </li>
                </ul>
            </div>
            [{/if}]


            [{if $oView->showQcpTrustedShopsCheckbox($sPaymentID)}]
                <input id="payolutionTerms" class='js-oxValidate js-oxValidate_notEmpty' name='payolutionTerms' type="checkbox" value="1" />[{ $oView->getQcpPayolutionTerms() }]
            [{/if}]

        </dd>
    </dl>
[{else}]
    [{$smarty.block.parent}]
[{/if}]