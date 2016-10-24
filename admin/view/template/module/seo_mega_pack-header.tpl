<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php if( isset( $action ) ) { ?>
					<a onclick="$('#form').submit();" type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></a>
				<?php } ?>
				<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			
			<h1><?php echo $heading_title; ?></h1>
			
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid seo-mega-pack">
		<?php require DIR_TEMPLATE . 'module/seo_mega_pack-messages.tpl'; ?>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body" id="mf-main-content">
				<?php if( isset( $action ) ) { ?><form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal"><?php } ?>
					<?php require DIR_TEMPLATE . 'module/seo_mega_pack-tabs.tpl'; ?>