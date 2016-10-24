
                    <script type="text/javascript" src="view/javascript/channels/jobs.js"></script>
<style>
#column-left.active #jobs_tab {
    display: block;
}
#jobs_tab {
    display: none;
    border-radius: 2px;
    color: #666666;
    background: #2b2b2b;
    margin: 15px 20px;
    padding: 5px 0;
}
#jobs_tab ul, #jobs_tab li {
    padding: 0;
    margin: 0;
    list-style: none;
}
#jobs_tab li {
    font-size: 11px;
    color: #9d9d9d;
    padding: 5px 10px;
    border-bottom: 1px dotted #373737;
}
#jobs_tab div:first-child {
    margin-bottom: 4px;
}
#jobs_tab .progress {
    height: 3px;
    margin-bottom: 0;
}

#jobs_tab .th {
font-size: 12px;
font-weight: bold;
}

#jobs_tab .bt {
font-size: 12px;
}

#jobs_tab li.job {
padding-bottom: 15px;
}



</style>
                    <div id="jobs_tab" data-action="<?php echo $jobs_service; ?>" class="hide">
                        <ul>
                            <li class="th"><span>Running now <b>0</b> job(s)</span></li>
                        </ul>
						<ul class="jobs"></ul>
                    </div>
			    
<div id="stats">
  <ul>
    <li>
      <div><?php echo $text_complete_status; ?> <span class="pull-right"><?php echo $complete_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $complete_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $complete_status; ?>%"> <span class="sr-only"><?php echo $complete_status; ?>%</span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_processing_status; ?> <span class="pull-right"><?php echo $processing_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $processing_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $processing_status; ?>%"> <span class="sr-only"><?php echo $processing_status; ?>%</span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_other_status; ?> <span class="pull-right"><?php echo $other_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $other_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $other_status; ?>%"> <span class="sr-only"><?php echo $other_status; ?>%</span> </div>
      </div>
    </li>
  </ul>
</div>
