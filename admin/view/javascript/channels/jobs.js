/**
 * Created by Ion on 4/22/2015.
 */
$( document ).ready(function() {
    refreshJobs();
    setInterval(refreshJobs, 3000);
});

function refreshJobs() {
    $.get($('#jobs_tab').attr('data-action'), {'type' : 'GET_ALL'}, function(json){
        var htmlJobs = '';
        if(json.status) {
            if(json.total > 0) {
                $('#jobs_tab').removeClass('hide');
                $('#jobs_tab .th span b').html(json.total);
                $.each(json.data, function(key, job){
                    htmlJobs += '<li class="job"><div>' + job.name + '</div><div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' + job.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + job.percent + '%"> <span class="sr-only">' + job.percent + '%</span> </div></div><div><span class="pull-right bt">' + job.label + ' <a href="#" data-id="' + job.id  + '" class="rj text-danger"><i class="fa fa-times-circle-o"></i></a></span></div></li>';
                });

            } else {
                $('#jobs_tab').addClass('hide');
            }
        } else {
            $('#jobs_tab').addClass('hide');
        }
        $('#jobs_tab .job').remove();
        var jobs = $(htmlJobs);
        $('#jobs_tab .jobs').append(jobs);

        $('a.rj', jobs).click(function(){
            var btn = $(this);
            var jid = btn.attr('data-id');
            if(confirm("Do you wont to delete this job!")) {
                $.get($('#jobs_tab').attr('data-action'), {'type' : 'DELETE_JOB', 'job_id' : jid}, function(r){
                    btn.closest('li.job').remove();
                });
            }

            return false;
        });


    } );
}