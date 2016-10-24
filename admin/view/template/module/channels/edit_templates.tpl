<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a onclick="$('#template_form').submit();" class="btn btn-success"><?php echo $button_save; ?></a>
            </div>
            <h1><?php echo $heading_title; ?> - <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">

        <?php if ($ebay_global_message) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $ebay_global_message; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_attention) { ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $error_attention; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>

        <?php } ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-size-bigger">
            <li><a href="<?php echo $tab_dashboard; ?>" ><i class="fa fa-tachometer bigger-130"></i> <span class="bigger-110"> Dashboard</span></a></li>
            <li><a href="<?php echo $tab_products; ?>" ><i class="fa fa-gavel bigger-130"></i> <span class="bigger-110"> Products</span></a></li>
            <li><a href="<?php echo $tab_selling; ?>" ><i class="fa fa-shopping-cart bigger-130"></i> <span class="bigger-110"> My Selling</span></a></li>
            <li><a href="<?php echo $tab_listing_profiles; ?>" ><i class="fa fa-list bigger-130"></i> <span class="bigger-110"> Listings</span></a></li>
            <li><a href="<?php echo $tab_ebay_account; ?>" ><i class="fa fa-user bigger-130"></i> <span class="bigger-110"> Account</span></a></li>
            <li><a href="<?php echo $tab_ebay_syncronize; ?>" ><i class="fa fa-tasks bigger-130"></i> <span class="bigger-110"> Tasks</span></a></li>
            <li class="active"><a href="<?php echo $tab_ebay_templates; ?>" ><i class="fa fa-desktop bigger-130"></i> <span class="bigger-110"> Templates</span></a></li>
            <li><a href="<?php echo $tab_feedback; ?>" ><i class="fa fa-comments bigger-130"></i> <span class="bigger-110"> Feedback</span></a></li>
            <li><a href="<?php echo $tab_ebay_settings; ?>"><i class="fa fa-cogs bigger-130"></i> <span class="bigger-110"> Settings</span></a></li>
            <li><a href="<?php echo $tab_ebay_logs; ?>"><i class="fa fa-history bigger-130"></i> <span class="bigger-110"> Logs</span></a></li>
        </ul>

        <div  id="tab-content" class="tab-content pr">

            <div class="tab-content pr">


                <h2>Template</h2>
                <form id="template_form" action="<?php echo $edit_template_url; ?>" method="POST">
                    <table class="form ebay table">
                        <tr>
                            <td> <strong><span class="required">*</span>Name:</strong></td>
                            <td>
                                <?php if (isset($template['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
                                <?php } ?>
                                <input type="text" name="name" class="form-control" size="80" value="<?php echo $template['name']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-title">Title</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-description">Description</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-image">Image</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-images">Images</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-attributes">Atributes</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-store_category_name" disabled="disabled">Store Category Name</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-store_category_id" disabled="disabled">Store Category ID</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-store_category_path" disabled="disabled">Store Category Path</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-category_id" disabled="disabled">Category ID</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-category_path" disabled="disabled">Category Path</button><br>
                                <button class="col-sm-9 col-md-9 tag btn btn-info" type="button" data-tag="tag-related_items" disabled="disabled">Related Items</button><br>



                                <div id="tag-title" class="hide">{{name}}</div>
                                <div id="tag-description" class="hide">{{description}}</div>
                                <div id="tag-image" class="hide">{{image}}</div>

                                <div id="tag-images" class="hide">
                                    {% for image in img_tpl %}
                                    <img src="{{ image }}" />
                                    {% endfor %}
                                </div>

                                <div id="tag-attributes" class="hide">
                                    <div>
                                        {% for attrbute_group in attrbute_groups %}
                                        <div class="group_name">
                                            {{ attrbute_group.name }}
                                        </div>
                                        <div>
                                            {% for attribute in attrbute_group.attrbutes %}
                                            <div class="name">{{attribute.name}}</div>
                                            <div class="value">{{attribute.value}}</div>
                                            {% endfor %}
                                        </div>
                                        {% endfor %}
                                    </div>
                                </div>

                            </td>
                            <td style="vertical-align: top;">
                                <textarea name="html" cols="150" rows="100" id="code"><?php echo $template['html']; ?></textarea>
                            </td>
                        </tr>
                    </table>
                </form>



            </div>

        </div>


    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        mode: "text/html",
        autoCloseTags: true,
        lineNumbers: true,
        tabMode: "indent",
        lineWrapping: true,
        indentUnit: 4
    });

    $('button.tag').click(function(){
        editor.replaceSelection($('#' + $(this).attr('data-tag')).html());

    });

</script>

