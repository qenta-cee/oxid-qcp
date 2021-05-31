[{if $oView->isQcpPaymentError() === TRUE}]
<div class="status error">[{ $oView->getQcpPaymentError() }]</div>
[{else}]
[{$smarty.block.parent}]
[{/if}]