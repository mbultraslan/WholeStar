<?php echo $header; ?>

<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($ebay_global_message) { ?>
    <div class="attention"><?php echo $ebay_global_message; ?></div>
    <?php } ?>
    <?php if ($error_attention) { ?>
    <div class="attention"><?php echo $error_attention; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1><img src="view/image/channels/ebay.png" alt="" /> <?php echo $heading_title; ?> - <?php echo $version; ?></h1>
        </div>
        <div class="content">

            <div class="vtabs">
                <a href="<?php echo $tab_dashboard; ?>"><img src="view/image/channels/dashboard.png"/>Dashboard</a>
                <a href="<?php echo $tab_products; ?>"><img src="view/image/channels/listings.png"/>Products</a>
                <a href="<?php echo $tab_selling; ?>"><img src="view/image/channels/sell_32.png"/> <div>My Selling</div></a>
                <a href="<?php echo $tab_listing_profiles; ?>"><img src="view/image/channels/profile_listing.png"/>Listings</a>

                <a href="<?php echo $tab_shipping_profiles; ?>" class="selected"><img src="view/image/channels/profile_shipping.png"/>Shipping</a>
                <a href="<?php echo $tab_listing_profiles; ?>"><img src="view/image/channels/creditcard.png"/>Payment</a>
                <a href="<?php echo $tab_listing_profiles; ?>"><img src="view/image/channels/redirect.png"/>Returns</a>

                <a href="<?php echo $tab_ebay_account; ?>"><img src="view/image/channels/accounts.png"/>Account</a>
                <a href="<?php echo $tab_ebay_syncronize; ?>"><img src="view/image/channels/redirect.png" style="margin-bottom: 9px;margin-top: 8px;"/>Import</a>
                <a href="<?php echo $tab_ebay_templates; ?>"><img src="view/image/channels/graphic.png" />Templates</a>
                <a href="<?php echo $tab_feedback; ?>"><img src="view/image/channels/feedback.png" />Feedback</a>
                <a href="<?php echo $tab_ebay_settings; ?>"><img src="view/image/channels/settings.png" />Settings</a>
                <a href="<?php echo $tab_ebay_logs; ?>"><img src="view/image/channels/history.png" /><br>Logs</a>
            </div>
            <div class="pr vtabs-content">
                <h2 class="toolbar">
                    <span>Add Shipping profile</span>

                    <a href="#" style="margin-left: 5px!important;"  class="button button-primary save-btn">Save</a>
                    <a href="<?php echo $cancel_url; ?>" class="button">Cancel</a>
                </h2>

                <form id="edit-shipping-form" action="<?php echo $action_url; ?>" method="post">
                    <?php if (!empty($shipping_profile)) { ?> <input type="hidden" name="id" value="<?php echo $shipping_profile['id'] ?>">  <?php } ?>

                <div>
                    <div class="panel-heading">Shipping</div>
                    <div class="panel-content">
                        <table class="form ebay">
                            <tbody>
                            <tr>
                                <td><span class="required">*</span>Site:</td>
                                <td>
                                    <select name="site_id">
                                        <?php foreach ($ebay_sites as $ebay_site ) { ?>
                                        <option value="<?php echo $ebay_site['id']; ?>"  <?php if (!empty($shipping_profile) && $shipping_profile['site_id'] == $ebay_site['id']) { ?> selected="selected"  <?php } ?> ><?php echo $ebay_site['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Use as Default?</td>
                                <td>
                                    <input type="checkbox" name="is_default" value="1" <?php if (!empty($shipping_profile) && $shipping_profile['is_default']) { ?> checked <?php } ?> >
                                </td>
                            </tr>

                            <tr>
                                <td><span class="required">*</span>Name:</td>
                                <td>
                                    <input type="text" name="name" value="<?php echo (!empty($shipping_profile))? $shipping_profile['name'] : ''; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Shipping Terms in Description:<span class="help">Indicates whether details about shipping costs and arrangements are specified in the item description.</span></td>
                                <td>
                                    <input type="checkbox" name="shipping_terms_in_description" value="1" <?php if (!empty($shipping_profile) && $shipping_profile['shipping_terms_in_description']) { ?> checked <?php } ?>>
                                </td>
                            </tr>

                            <tr>
                                <td>Description:</td>
                                <td>
                                    <textarea name="description" cols="40" rows="5" class="w400"><?php echo (!empty($shipping_profile))? $shipping_profile['description'] : ''; ?></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td>Country:</td>
                                <td>
                                    <select name="country">
                                        <?php foreach ($ebay_countries as $ebay_country ) { ?>
                                        <option value="<?php echo $ebay_country['code']; ?>" <?php if (!empty($shipping_profile) && $shipping_profile['country'] == $ebay_country['code']) { ?> selected="selected"  <?php } ?>><?php echo $ebay_country['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Zip/Postcode: <span class="help">If you do not specify Zip/Postcode, you must specify Location</span></td>
                                <td>
                                    <input type="text" name="postal_code" id="postal_code" value="<?php echo (!empty($shipping_profile))? $shipping_profile['postal_code'] : ''; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Location:<span class="help">Indicates the geographical location of the item (along with Country) to be displayed on eBay listing pages.</span></td>
                                <td>
                                    <input type="text" name="location" id="location" value="<?php echo (!empty($shipping_profile))? $shipping_profile['location'] : ''; ?>">
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="panel-heading">Services</div>
                    <div class="panel-content">
                        <table class="form ebay">
                            <tbody>
                            <tr>
                                <td><span class="required">*</span>Shipping Type:</td>
                                <td>
                                    <select name="service_type">
                                        <?php foreach ($shipping_service_types as $shipping_service_type) { ?>
                                        <option <?php if (!empty($shipping_profile) && $shipping_profile['service_type'] == $shipping_service_type['name']) { ?>selected<?php } ?> value="<?php echo $shipping_service_type['name']; ?>"><?php echo $shipping_service_type['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Offer International Shipping?</td>
                                <td>
                                    <input type="checkbox" name="has_international_shipping" value="1" <?php if (!empty($shipping_profile) && $shipping_profile['has_international_shipping']) { ?> checked <?php } ?>>
                                </td>
                            </tr>

                            <tr id="package_type" class="hide">
                                <td>Package Type:</td>
                                <td>
                                    <div>
                                        <select name="package_type">
                                            <?php foreach ($shipping_packages as $shipping_package) { ?>
                                            <option value="<?php echo $shipping_package['code']; ?>" <?php if (!empty($shipping_profile) && $shipping_profile['package_type'] == $shipping_package['code']) { ?>selected="selected"<?php } ?>><?php echo $shipping_package['label']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr id="dimensions" class="hide">
                                <td>Package Dimensions:</td>
                                <td>
                                    <div>
                                        <span class="required">*</span>Depth:<input name="dimension_depth" class="enumeric" value="<?php echo (!empty($shipping_profile))? $shipping_profile['dimension_depth'] : ''; ?>">
                                        <span class="required">*</span>Length:<input name="dimension_length" class="enumeric" value="<?php echo (!empty($shipping_profile))? $shipping_profile['dimension_length'] : ''; ?>">
                                        <span class="required">*</span>Width:<input name="dimension_width" class="enumeric" value="<?php echo (!empty($shipping_profile))? $shipping_profile['dimension_width'] : ''; ?>">
                                        <input type="checkbox" id="IsIrregularPackage"  name="is_irregular_package" value="1" <?php if (!empty($shipping_profile) && $shipping_profile['is_irregular_package']) { ?> checked <?php } ?> > <label for="IsIrregularPackage">Irregular package size</label>
                                    </div>
                                    <span class="help">(enter dimensions in inches)</span>
                                </td>
                            </tr>

                            <tr id="package_weight" class="hide">
                                <td>Package Weight:</td>
                                <td>
                                    <div style="position: relative;">
                                        <span class="required">*</span>Major(lbs):<input name="weight_major" class="enumeric" value="<?php echo (!empty($shipping_profile))? $shipping_profile['weight_major'] : ''; ?>">
                                        <span class="required">*</span>Minor(oz):<input name="weight_minor" class="enumeric" value="<?php echo (!empty($shipping_profile))? $shipping_profile['weight_minor'] : ''; ?>">
                                        <div class="margin-top-15" style="width: 430px">
                                            <div id="weight_slider"></div>
                                        </div>
                                        <div class="prew">
                                            <img class="pack" src="view/image/channels/pack1.png" width="50" height="50">
                                            <div class="weight_prew_label">
                                                <div></div>
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr id="package_costs" class="hide">
                                <td>Package handling fees:</td>
                                <td>
                                    <input type="text" class="eprice" name="package_handling_fee" value="<?php echo (!empty($shipping_profile))? $shipping_profile['package_handling_fee'] : ''; ?>">
                                    <div class="help">(Fees a seller might assess for the shipping of the item.)</div>
                                </td>
                            </tr>

                            <tr>
                                <td>Handling Time:</td>
                                <td>
                                    <select name="dispatch_time_max">
                                        <?php foreach ($dispatch_times as $time) { ?>
                                        <option value="<?php echo ($time['code'] > 0)? $time['code'] : ''; ?>" <?php if (!empty($shipping_profile) && $shipping_profile['dispatch_time_max'] == $time['code']) { ?>selected="selected"<?php } ?> ><?php echo $time['label']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td style="vertical-align: top;">
                                    Domestic shipping services<br><br>
                                    <button id="add-domestic-service" class="button button-primary">Add new service</button>
                                </td>
                                <td id="domestic-services">

                                    <div id="domestic-template" class="hide">
                                        <div class="s"><label>Service:</label>
                                            <select class="flat">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($domestic_flat_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>

                                            <select class="calculated hide">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($domestic_calculated_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="c"><label>Cost:</label> <input class="w50 cost eprice" type="text"> <label>Each Additional:</label> <input class="w50 additional_cost eprice" type="text">  <label>Free Shipping?</label><input class="free_shipping" type="checkbox"> <button class="remove button button-danger">Remove</button></div>
                                    </div>


                                    <div class="ssl">
                                        <div class="s"><label>Service:</label>
                                            <select name="services[domestic_service][0][flat]" class="flat">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($domestic_flat_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>

                                            <select name="services[domestic_service][0][calculated]" class="calculated hide">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($domestic_calculated_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="c"><label>Cost:</label> <input class="w50 cost eprice" name="services[domestic_service][0][shipping_cost]" type="text"> <label>Each Additional:</label> <input class="w50 additional_cost eprice" name="services[domestic_service][0][additional_cost]" type="text">  <label>Free Shipping?</label><input class="free_shipping" name="services[domestic_service][0][free_shipping]"  type="checkbox"> <button class="remove button button-danger">Remove</button></div>
                                    </div>
                                </td>
                            </tr>

                            <tr id="international_shipping_services" class="hide">
                                <td style="vertical-align: top;">
                                    International shipping services<br><br>
                                    <button id="add-international-service" class="button button-primary">Add new service</button>
                                </td>
                                <td id="international-services">
                                    <div id="international-template" class="hide">
                                        <div class="s"><label>Service:</label>
                                            <select class="flat">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($international_flat_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <select class="calculated hide">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($international_calculated_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="c"><label>Cost:</label> <input class="w50 cost eprice" type="text"> <label>Each Additional:</label> <input class="w50 additional_cost eprice" type="text">  <label>Free Shipping?</label><input class="free_shipping" type="checkbox"> <button class="remove button">Remove</button></div>

                                        <div class="z">
                                            <label>Shipping locations:</label>
                                            <div class="box">
                                                <?php foreach ($ebay_shipping_locations as $ebay_shipping_location ) { ?>
                                                <span><input class="shipping_location" type="checkbox" value="<?php echo $ebay_shipping_location['code']; ?>"> <label><?php echo $ebay_shipping_location['name']; ?></label></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ssl">
                                        <div class="s"><label>Service:</label>
                                            <select name="services[international_service][0][flat]" class="flat">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($international_flat_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>

                                            <select name="services[international_service][0][calculated]" class="calculated hide">
                                                <option value="">-- Choose a service --</option>
                                                <?php foreach ($international_calculated_shipping_services as $shipping_service) { ?>
                                                <option value="<?php echo $shipping_service['shipping_service']; ?>"><?php echo $shipping_service['description']; ?></option>
                                                <?php } ?>
                                            </select>


                                        </div>
                                        <div class="c"><label>Cost:</label> <input class="w50 cost eprice" name="services[international_service][0][shipping_cost]" type="text"> <label>Each Additional:</label> <input class="w50 additional_cost eprice" name="services[international_service][0][additional_cost]" type="text">  <label>Free Shipping?</label><input class="free_shipping" name="services[international_service][0][free_shipping]"  type="checkbox"> <button class="remove button">Remove</button></div>

                                        <div class="z">
                                            <label>Shipping locations:</label>
                                            <div class="box">
                                                <?php foreach ($ebay_shipping_locations as $ebay_shipping_location ) { ?>
                                                   <span><input class="shipping_location" type="checkbox" name="services[international_service][0][shipping_location][]" value="<?php echo $ebay_shipping_location['code']; ?>"> <label><?php echo $ebay_shipping_location['name']; ?></label></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
                </form>

                <h2 class="toolbar">
                    <a href="#" style="margin-left: 5px!important;" class="button button-primary save-btn">Save</a>
                    <a href="<?php echo $cancel_url; ?>" class="button">Cancel</a>
                </h2>


            </div>
        </div>



    </div>
</div>
</div>

<style>



</style>


<div id="jobs_tab" data-action="<?php echo $jobs_service; ?>" class="hide">
    <div class="th">
        <div id="circleG"><div class="cg1 circleG"></div></div>
        <span>Running now <b>0</b> job(s)</span>
    </div>
</div>

<?php echo $footer; ?>


