<div>
	<p>Shown below are the features supported by the primary category you chose for the template. Click "Next" to view the estimated listing fees.</p>
	<div>
		<?php if($profile['variations_enabled']) {?>
			<div>
				<img src="view/image/channels/success.png" width="16" height="16" />
				<span>Products with product options can be listed in this category</span>
			</div>
		<?php } else {?>
			<div>
				<img src="view/image/channels/warning.png" width="16" height="16" />
				<span>Products with product options can't be listed in this category</span>
			</div>
		<?php }?>
		
		<?php if($profile['return_policy_enabled']) {?>
			<div>
				<img src="view/image/channels/success.png" width="16" height="16" />
				<span>A return policy can be specified</span>
			</div>
		<?php } ?>
		
		<?php if($profile['revise_quantity_allowed']) {?>
			<div>
				<img src="view/image/channels/success.png" width="16" height="16" />
				<span>A lot size can be set</span>
			</div>
		<?php } ?>
		
		<?php if($profile['handling_time_enabled']) {?>
			<div>
				<img src="view/image/channels/success.png" width="16" height="16" />
				<span>Handling time can be set</span>
			</div>
		<?php } ?>
		
		<?php if($profile['revise_price_allowed']) {?>
			<div>
				<img src="view/image/channels/tax.gif" width="16" height="16" /> A reserve price can be set for the listing (minimum reserve of <?php echo number_format($profile['minimum_reserve_price'], 2, '.', '') ?>)	
			</div>
		<?php } ?>
		
		
		<?php if($profile['paypal_required']) {?>
			<div>
				<img src="view/image/channels/payment.gif" width="16" height="16" />
				<span>PayPal is required as a payment method</span>
			</div>
		<?php } ?>
		
		
	</div>
</div>