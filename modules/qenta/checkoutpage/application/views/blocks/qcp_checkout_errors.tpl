[{if $oView->isWcpPaymentError() === TRUE}]
<div class="status error">[{ $oView->getWcpPaymentError() }]</div>
[{else}]
[{$smarty.block.parent}]
[{/if}]