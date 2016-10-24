<div class="newsletter--parallax parallax-banner" style="background-image: url(image/catalog_images/newsletter%202.jpg);">
<div class="container">
<div class="row">
<div class="col-md-12 col-xs-12">
<div class="newsletter--parallax__content">
<i class="pe-7s-mail"></i>
<h4 class="newsletter--parallax__content__title"><span style="line-height: 18px;">Sign up for news & Special offers</span><br>
</h4>
<form name="subscribe" id="subscribe"  class="newsletter--parallax__form">
  <div class="input-group" id="search1">
    <input type="text" class="form-control input-lg newsletter--parallax__form__input" id="subscribe_email" placeholder="Enter Your Email Address" value="" name="subscribe_email">
    <span class="input-group-btn">
    <button class="btn btn-default btn-lg newsletter--parallax__form__submit" onclick="email_subscribe()" type="button" style="padding:10px 10px;text-transform:uppercase; color:#fff; height:42px;">Sign Up</button>
    </span> </div>
  <div id="subscribe_result"></div>
  </div>
  </div>
  </div>
  </div>
  </div>
  
  <!--<table border="0" cellpadding="2" cellspacing="2">

   <tr>

     <td align="left"><span class="required">*</span>&nbsp;<?php echo $entry_email; ?><br /><input type="text" value="" name="subscribe_email" id="subscribe_email"></td>

   </tr>

   <tr>

     <td align="left"><?php echo $entry_name; ?>&nbsp;<br /><input type="text" value="" name="subscribe_name" id="subscribe_name"> </td>

   </tr>

   <?php 

     for($ns=1;$ns<=$option_fields;$ns++) {

     $ns_var= "option_fields".$ns;

   ?>

   <tr>

    <td align="left">

      <?php 

       if($$ns_var!=""){

         echo($$ns_var."&nbsp;<br/>");

         echo('<input type="text" value="" name="option'.$ns.'" id="option'.$ns.'">');

       }

      ?>

     </td>

   </tr>

   <?php 

     }

   ?>

   <tr>

     <td align="left">

     <a class="button" onclick="email_subscribe()"><span><?php echo $entry_button; ?></span></a><?php if($option_unsubscribe) { ?>

          <a class="button" onclick="email_unsubscribe()"><span><?php echo $entry_unbutton; ?></span></a>

      <?php } ?>    

     </td>

   </tr>

   <tr>

     <td align="center" id="subscribe_result"></td>

   </tr>

  </table>-->
  
</form>
<script language="javascript">

	<?php 

  		if(!$thickbox) { 

	?>	

function email_subscribe(){

	$.ajax({

			type: 'post',

			url: 'index.php?route=module/newslettersubscribe/subscribe',

			dataType: 'html',

            data:$("#subscribe").serialize(),

			success: function (html) {

				eval(html);

			}}); 

}

function email_unsubscribe(){

	$.ajax({

			type: 'post',

			url: 'index.php?route=module/newslettersubscribe/unsubscribe',

			dataType: 'html',

            data:$("#subscribe").serialize(),

			success: function (html) {

				eval(html);

			}}); 

}

   <?php }else{ ?>

function email_subscribe(){

	$.ajax({

			type: 'post',

			url: 'index.php?route=module/newslettersubscribe/subscribe',

			dataType: 'html',

            data:$("#subscribe").serialize(),

			success: function (html) {

				eval(html);

			}}); 

}

function email_unsubscribe(){

	$.ajax({

			type: 'post',

			url: 'index.php?route=module/newslettersubscribe/unsubscribe',

			dataType: 'html',

            data:$("#subscribe").serialize(),

			success: function (html) {

				eval(html);

			}}); 

}

   <?php } ?>

   

</script> 
