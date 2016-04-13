[{oxstyle include="css/checkout.css"}]
[{capture append="oxidBlock_content"}]
[{* ordering steps *}]
[{include file="page/checkout/inc/steps.tpl" active=3 }]
<div id="checkout_iframe" style="margin:auto;">
    <iframe src="[{$wcpIFrameUrl}]" width="680" height="660" name="wcpIFrame" frameborder="0" style="margin: auto;">
    </iframe>
</div>
[{ insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{include file="layout/page.tpl" title=$template_title location=$template_title}]
