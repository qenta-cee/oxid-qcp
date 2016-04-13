[{include file="headitem.tpl" }]
[{if $sErrorMessage || $sSuccessMessage}]
<div class="messagebox">
    [{if $sErrorMessage}]<p class="warning">[{ $sErrorMessage }][{/if}]</p>
    [{if $sSuccessMessage}]<p class="message">[{ $sSuccessMessage }][{/if}]</p>
</div>
[{/if}]
<form method="post" action="[{ $oViewConf->getSelfLink() }]" name="wcp_config_export_form">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="wcp_submit_config">
    <input type="hidden" name="fnc" value="submit">

    <label
            for="wcp_config_export_recipient"
            style="display:block"><strong>[{ oxmultilang ident="MODULE_PAYMENT_WCP_EXPORT_CONFIG_RECEIVER" }]</strong></label>
    <select name="wcp_config_export_recipient">
        [{foreach from=$aSupportMails item=mailAddress}]
        [{if $sSupportMailActive == $mailAddress}]
            <option value="[{$mailAddress}]" selected="selected">[{$mailAddress}]</option>
        [{else}]
            <option value="[{$mailAddress}]">[{$mailAddress}]</option>
        [{/if}]
        [{/foreach}]
    </select>
<br/>
<br/>
    <label
            for="wcp_config_export_config_string"
            style="display:block"><strong>[{ oxmultilang ident="MODULE_PAYMENT_WCP_EXPORT_CONFIG_CONFIG_STRING" }]</strong></label>
                                    <textarea name="wcp_config_export_config_string"
                                              cols="80"
                                              rows="20"
                                              style="overflow: scroll;"
                                            >[{$oView->getModuleConfig()}]</textarea>


<br/>
<br/>
    <label
            for="wcp_config_export_description_text"
            style="display:block"><strong>[{ oxmultilang ident="MODULE_PAYMENT_WCP_EXPORT_CONFIG_DESC_TEXT" }]</strong></label>
                                    <textarea name="wcp_config_export_description_text"
                                              cols="80"
                                              rows="20"
                                            >[{$sDescriptionText}]</textarea>


<br/>
<br/>
    <label
            for="wcp_config_export_reply_to_mail"
            style="display:block"><strong>[{ oxmultilang ident="MODULE_PAYMENT_WCP_EXPORT_CONFIG_RETURN_MAIL" }]</strong></label>
    <input type="text"
           value="[{$sReplyTo}]"
           name="wcp_config_export_reply_to_mail"
           size="80">
<br/>
<br/>
    <input class="submitButton largeButton"
           type="submit"
           value="[{ oxmultilang ident="WCP_SUBMIT" }]"
           name="submit">

</form>

[{include file="bottomitem.tpl"}]