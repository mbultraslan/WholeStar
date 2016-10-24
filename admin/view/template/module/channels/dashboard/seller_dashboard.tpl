<table class="table">
	<tr>
		<th align="left">eBay Official Time</th>
		<td align="right">-</td>
	</tr>
	<tr>
		<th align="left">BuyerSatisfaction</th>
		<td align="right"><?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
		<?php if($seller_dashboard['name'] == 'buyerSatisfaction') {?> <?php echo $seller_dashboard['status'];?>
		<?php }?> <?php }?></td>
	</tr>
	<tr>
		<th align="left">PowerSeller status</th>
		<td align="right"><?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
		<?php if($seller_dashboard['name'] == 'powerSellerStatus') {?> <?php echo $seller_dashboard['level'];?>
		<?php }?> <?php }?></td>
	</tr>
	<tr>
		<th align="left">Search Standing</th>
		<td align="right"><?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
		<?php if($seller_dashboard['name'] == 'searchStanding') {?> <?php echo $seller_dashboard['status'];?>
		<?php }?> <?php }?></td>
	</tr>
	<tr>
		<th align="left">Seller Account</th>
		<td align="right"><?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
		<?php if($seller_dashboard['name'] == 'sellerAccount') {?> <?php echo $seller_dashboard['status'];?>
		<?php }?> <?php }?></td>
	</tr>
	<tr>
		<th align="left">Seller Fee Discount</th>
		<td align="right"><?php foreach ($dashboard['seller_dashboard'] as $seller_dashboard) {?>
		<?php if($seller_dashboard['name'] == 'sellerFeeDiscount') {?> <?php echo $seller_dashboard['percent'];?>
		% <?php }?> <?php }?></td>
	</tr>
</table>
