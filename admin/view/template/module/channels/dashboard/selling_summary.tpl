<table class="table">
	<tr>
		<th align="left">Your selling activity</th>
		<th align="right">Items</th>
		<th align="right">Amount</th>
	</tr>
	<tr>
		<td>You have sold</td>
		<td align="right"><?php echo $dashboard['selling_summary']['total_sold_count'];?>
		</td>
		<td align="right"><?php echo $dashboard['selling_summary']['total_sold_value_currency'];?>
		<?php echo $dashboard['selling_summary']['total_sold_value'];?></td>
	</tr>
	<tr>
		<td>You can list</td>
		<td align="right"><?php echo $dashboard['selling_summary']['quantity_limit_remaining'];?></td>
		<td align="right"><?php echo $dashboard['selling_summary']['amount_limit_remaining_currency'];?>
		<?php echo $dashboard['selling_summary']['amount_limit_remaining'];?></td>
	</tr>
</table>
