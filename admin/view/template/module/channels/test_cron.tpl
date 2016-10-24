<table class="table">
    <tr>
        <td colspan="2"><strong>System information:</strong></td>
    </tr>
    <tr>
        <td>Max Execution Time:</td>
        <td>
            <strong>
               <?php echo $max_execution_time; ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>shell_exec() function status:</td>
        <td>
            <strong>
                <p class="<?php echo $shell_exec_status ?>">
                    <?php echo ($shell_exec_status=='Enabled') ? '<span style="color:#4CC417">Enabled</span>' : '<span style="color:#F70D1A">Disabled</span>'; ?>
                </p>
            </strong>
        </td>
    </tr>
    <tr><td><?php  echo $cron_folder;?></td>
        <td>
            <strong>
                <p class="<?php echo $folder_permission ?>">
                    <?php echo ($folder_permission=='Writable') ? '<span style="color:#4CC417">Writable</span>' : '<span style="color:#F70D1A">Unwritable</span>'; ?>
                </p>
            </strong>
        </td>
    </tr>
    <tr>
        <td>Cron job status:</td>
        <td>
            <strong>
                <p class="<?php echo $cronjob_status ?>">
                    <?php echo ($cronjob_status=='Enabled') ? '<span style="color:#4CC417">Enabled</span>' : '<span style="color:#F70D1A">Disabled</span>'; ?>
                </p>
            </strong>
        </td>
    </tr>
    <?php if ($shell_exec_status='Enabled' && $folder_permission=='Writable' && $cronjob_status=='Enabled') { ?>
    <tr>
        <td colspan="2"><strong>Current cron jobs:<strong></td>
    </tr>
    <?php if(isset($current_cron_jobs)) {
                foreach($current_cron_jobs as $cron_job) {
                    if(!empty($cron_job)) { ?>
    <tr><td colspan="2"><p class="cronJobEntry"><?php  echo $cron_job; ?></td></tr>
    <?php }   }	} ?>
    <?php } ?>
</table>