<table class="table">
	    		<tr>
	    			<td></td>
	    			<td></td>
	    			<th align="right">1 Months</th>
	    			<th align="right">6 Months</th>
	    			<th align="right">12 Months</th>
	    		</tr>
	    		<tr>
	    			<td>Positive</td>
	    			<td><img src="view/image/channels/iconPos_16x16.gif"/></td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'positive' && $feedback_summary['period_in_days'] == 30) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'positive' && $feedback_summary['period_in_days'] == 180) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'positive' && $feedback_summary['period_in_days'] == 365) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    		</tr>
	    		<tr>
	    			<td>Neutral</td>
	    			<td><img src="view/image/channels/iconNeu_16x16.gif"/></td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'neutral' && $feedback_summary['period_in_days'] == 30) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'neutral' && $feedback_summary['period_in_days'] == 180) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'neutral' && $feedback_summary['period_in_days'] == 365) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    		</tr>
	    		<tr>
	    			<td>Negative</td>
	    			<td><img src="view/image/channels/iconNeg_16x16.gif"/></td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'negative' && $feedback_summary['period_in_days'] == 30) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'negative' && $feedback_summary['period_in_days'] == 180) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    			<td align="right">
	    				<?php foreach ($dashboard['feedback_summary'] as $feedback_summary) {?>
	    					<?php if($feedback_summary['name'] == 'negative' && $feedback_summary['period_in_days'] == 365) {?>
	    						<?php echo $feedback_summary['count'];?>
	    					<?php }?>
	    				<?php }?>
	    			</td>
	    		</tr>
	    		
	    	</table>