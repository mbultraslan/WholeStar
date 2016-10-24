<table class="table">
	<tr>
		<th align="right"></th>
		<th align="right">30 Days</th>
		<th align="right"></th>
		<th align="right">52 Weeks</th>
		<th align="right"></th>
	</tr>

	<?php foreach ($dashboard['seller_rating_summary'] as $seller_rating_summary) {?>
	<tr>
		<th align="left"><?php echo $seller_rating_summary['rating_detail'];?>
		</th>
		<td align="right">
		<div class="rate"
			data-score="<?php echo $seller_rating_summary['ThirtyDays']['rating'];?>"></div>
		</td>
		<th align="left"><?php echo $seller_rating_summary['ThirtyDays']['rating_count'];?>
		</th>

		<td align="right">
		<div class="rate"
			data-score="<?php echo $seller_rating_summary['FiftyTwoWeeks']['rating'];?>"></div>
		</td>
		<th align="left"><?php echo $seller_rating_summary['FiftyTwoWeeks']['rating_count'];?>
		</th>
	</tr>
	<?php }?>

</table>
