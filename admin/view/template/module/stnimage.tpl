<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

    <div class="page-header">

        <div class="container-fluid">

            <div class="pull-right">

                <button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>

                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>

            <h1><?php echo $heading_title; ?></h1>

            <ul class="breadcrumb">

                <?php foreach ($breadcrumbs as $breadcrumb) { ?>

                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

                <?php } ?>

            </ul>

        </div>

    </div>

    <div class="container-fluid">

        <?php if ($error_warning) { ?>

        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>

        </div>

        <?php } ?>

        <div class="panel panel-default">

            <div class="panel-heading">

                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>

            </div>

            <div class="panel-body">



                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">



                    <div class="form-group">

                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>

                        <div class="col-sm-10">

                            <select name="status" id="input-status" class="form-control">

                                <?php if ($status) { ?>

                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                                <option value="0"><?php echo $text_disabled; ?></option>

                                <?php } else { ?>

                                <option value="1"><?php echo $text_enabled; ?></option>

                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <table id="images" class="table table-striped table-bordered table-hover">

                        <thead>

                            <tr>

                                <!--td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td-->

                                <td class="text-left"><?php echo $entry_title; ?></td>

                                <td class="text-left"><?php echo $entry_image; ?></td>

                                <td class="text-left"><?php echo $entry_link; ?></td>

                                <td class="text-left"><?php echo $entry_width; ?></td>

                                <td class="text-right"><?php echo $entry_sort_order; ?></td>

                            </tr>

                        </thead>

                        <tbody>

                            <?php $image_row = 0; ?>



                            <?php foreach ($stnimages as $stnimage) {

                            //echo "<pre>";

                            //print_r($stnimage);

                            ?>

                            <tr id="image-row<?php echo $image_row; ?>">

                                <!--td class="text-center">

                                    <input type="checkbox" name="selected[]" value="<?php echo $stnimage['stn_image_id']; ?>" />

                                </td-->

                                <td><input type="text" name="stnimage['<?php echo $image_row ?>'][stnimage_description]; ?>][title]" placeholder="<?php echo $entry_title; ?>" class="form-control poptitle" value="<?php echo $stnimage['banner_title']; ?>"/></td>

                                <!--<td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $stnimage['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>

         <input type="hidden" name="stnimage[<?php echo $image_row; ?>][image]" value="" id="input-image<?php echo $image_row; ?>" /></td>-->

                                <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $stnimage['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="stnimage['<?php echo $image_row ?>'][image]" value="<?php echo $stnimage['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>

                                <td class="text-left"><input type="text" name="stnimage['<?php echo $image_row ?>'][link]" placeholder="<?php echo $entry_link; ?>" class="form-control subtitlepop"  value="<?php echo $stnimage['link']; ?>"/></td>

                                <!--td class="text-left"><input type="text" name="stnimage['<?php echo $image_row ?>'][width]" placeholder="<?php echo $entry_width; ?>" class="form-control"  value="<?php echo $stnimage['banner_width']; ?>" onkeypress="return isNumber(event)"/></td-->

                                <td class="text-right"><input type="text" name="stnimage['<?php echo $image_row ?>'][sort_order]" placeholder="<?php echo $entry_sort_order; ?>" class="form-control descriptionpop"  value="<?php echo $stnimage['sort_order']; ?>"/></td>

                                <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>

                            </tr>

                            <?php $image_row++; ?>

                            <?php } ?>

                        </tbody>

                        <tfoot>

                            <tr>

                                <td colspan="3"></td>
								 <td class="text-left"><button type="button" onclick="showPreview();" data-toggle="tooltip" title="Preview" class="btn btn-primary">Preview</button></td>
                                <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_banner_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>

                            </tr>

                        </tfoot>

                    </table>

                </form>

            </div>

        </div>

    </div>

</div>
<div class="popuppreview" style="display:none; position: fixed; top: 0px; left: 0px; background: rgba(0, 0, 0, 0.5) none repeat scroll 0% 0%; z-index: 99999; width: 100%; height: 100%;"></div>
<style>
.pop_mesjh {
    border-radius: 15px;
    box-shadow: 0 1px 2px 0 rgb(102, 102, 102);
    margin: 50px 30%;
    padding: 20px;
    width: 40%;
	background:#fff;
	float:left;
}
.chnge_proti{
	position:relative;	
}
.pop_mesjh img{width:100%;}
.chnge_proti h1 {
    border-bottom: 1px dotted rgb(227, 227, 227);
    font-size: 16px;
    margin: 7px 0 0;
    padding: 0 0 13px;
    text-transform: uppercase;
}
.pop_mesjh .chnge_proti .eclo {
    background: url("https://dressdirect.co.uk/catalog/view/theme/zorka/image/delete.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    cursor: pointer;
    height: 18px;
    overflow: hidden;
    position: absolute;
    right: 0px;
    text-indent: 50px;
    top: 0px;
    transition: all 0.2s ease 0s;
    width: 18px;
    z-index: 9;
}
.rotmg {
    float: left;
    text-align: center;
    width: 100%;
}
</style>
<script type="text/javascript"><!--

var image_row = <?php echo $image_row; ?> ;

			function showPreview(){
				$('.popuppreview').show();
				var titlepop = $('.poptitle').val();
				var subtitlepop = $('.subtitlepop').val();
				var descriptionpop = $('.descriptionpop').val();
				var imagepath = $('#input-image0').val();
				$html = '<div class="pop_mesjh">';
        $html += '<div class="chnge_proti">';
            $html += '<h1>'+titlepop+'</h1>';
            $html += '<a href="javascript:void(0);" id="eclo_blockuser" class="eclo">X</a>';
            $html += '<div id="errorblockname"></div>';
            $html += '<div class="rotm rotmg">';
                $html += '<div class="item">';
                    $html += '<div class="col-lg-12 col-md-12" style="padding-left:0;">';
                        $html += '<p style="text-align:left;"><span class="allign-rgt-title" style="padding-left:0;">'+subtitlepop+'</span></p>';
                       $html += ' <p style="text-align:left;"><span style="padding-left:0;">'+descriptionpop+'</span></p>';
                            $html += '<span class="allign-lft-img">';
                                $html += '<img src="https://dressdirect.co.uk/image/'+imagepath+'"/>';
                    $html += '</span></div>';
                $html += '</div>';                 
            $html += '</div>';
            $html += '<div class="clr"></div>';
        $html += '</div>';
    $html += '</div>';
	$('.popuppreview').html($html);
			}
			$(document).on('click','a#eclo_blockuser', function(){
				$('.popuppreview').hide();
			});

            function addImage() {

                html = '<tr id="image-row' + image_row + '">';

                html += '  <td><input type="text" name="stnimage[' + image_row + '][stnimage_description]; ?>][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" /></td>';

                html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="stnimage[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';

                html += '  <td class="text-left"><input type="text" name="stnimage[' + image_row + '][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>';

                //html += '  <td class="text-left"><input type="text" name="stnimage[' + image_row + '][width]" value="" placeholder="<?php echo $entry_width; ?>" class="form-control" onkeypress="return isNumber(event)"/></td>';

                html += '  <td class="text-right"><input type="text" name="stnimage[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" onkeypress="return isNumber(event)"/></td>';

                html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';

                html += '</tr>';

                $('#images tbody').append(html);

                image_row++;

            }



    function isNumber(evt) {

        evt = (evt) ? evt : window.event;

        var charCode = (evt.which) ? evt.which : evt.keyCode;

        if (charCode > 31 && (charCode < 48 || charCode > 57)) {

            return false;

        }

        return true;

    }



//--></script>

<?php echo $footer; ?>