<div>
	
	<?php if(!empty($errors)) { ?>
		<p>An errors occurred retrieving the estimated listing costs. Click <a href="<?php echo $edit_listing_profile;?>">Edit</a> to change your list.</p>
		<div class="form-container">
		<table class="table">
		<thead>
			<tr>
				<td align="center">Code</td>
				<td align="center">Message</td>
			</tr>
		</thead>
		<tbody>	
			<?php foreach ($errors as $error) { ?>
			<tr>
				<td align="left"><?php echo $error['code'];?></td>
				<td align="left">
				<?php echo $error['long_message'];?>
				<ul>
				<?php foreach ($error['parameters'] as $parameter) { ?>
					<li><?php echo $parameter['value'];?></li>
				<?php }?>
				</ul>
				</td>
			</tr>
			<?php }?>
		</tbody>	
		</table>
		</div>
	<?php } else { ?>
	
	<p>The approximate fees that will be charged to your eBay account for listing the selected products are shown below. Click the "Next" button below to start the listing process with eBay.</p>
	
	<p>All fees are shown in <b>USD</b> currency.</p>
	<table class="table" style="margin-bottom: 0;">
		<thead>
		<tr>
			<td align="left">Fee</td>
			<td align="right">Cost</td>
		</tr>
		</thead>
	</table>

	<div class="scroll-table">
	<table class="table">
        <tbody>
		<tr>
			<td><?php echo $listingFee['name'];?></td>
			<td align="right"><?php echo $listingFee['cost_label'];?></td>
		</tr>

		<?php foreach ($fees as $fee) { ?>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;<?php echo $fee['name'];?></td>
			<td align="right"><?php echo $fee['cost_label'];?></td>
		</tr>
		<?php }?>
        </tbody>
	</table>
	</div>

	<table class="table">
		<tfoot>
		<tr style="font-weight: bold;">
			<td style="background-color: #dbf3d1;"><?php echo $totalFee['name'];?></td>
			<td align="right" style="background-color: #dbf3d1;"><?php echo $totalFee['cost_label'];?></td>
		</tr>
		</tfoot>
	</table>
	
	<p>Important: Some extra fees may be applied by eBay for this listing.</p>
	<?php }?>
</div>