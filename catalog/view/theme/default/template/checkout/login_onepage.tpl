<?php $classtheme = ($css['checkout_theme'] == 'standar') ?  'warning' :  $css['checkout_theme'];  ?>

<form  method="post" enctype="multipart/form-data"  onsubmit="return false;">
    <div class="row">
        <div class="panel-body" style="border-left: 0">
            <div class="checkout-content-onepage">

                <div class="form-group form-group-sm required">
                    <label class="control-label"> <?php echo $entry_email; ?></label>
                    <input type="email" name="email" value="" class="form-control input-sm">
                </div>
                <div class="form-group form-group-sm required">
                    <label class="control-label" style="width: 100%"> <?php echo $entry_password; ?>  <span class="pull-right"><a href="<?php echo $forgotten; ?>"  target="_blank"><i class="fa fa-retweet"></i> <?php echo $text_forgotten; ?></a></span></label>
                    <input type="password" name="password" value=""  class="form-control input-sm"/>
                </div>

                <br>
                <button type="submit"  id="button-login" class="btn btn-<?php echo $classtheme; ?> btn-block" style="<?php
                        if (!empty($css['common_btn_color'])) {
                            echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
                            }
                            ?>"><i class="fa fa-sign-in"></i> <?php echo $button_login; ?></button>


                </div>

            </div>
        </div>
    </form>
