<?php
$kuler = Kuler::getInstance();

$kuler->addStyle($kuler->getThemeResource('catalog/view/javascript/kuler/jquery-ui/jquery-ui.min.css'), true);
$kuler->addStyle($kuler->getThemeResource('catalog/view/javascript/kuler/jquery-ui/jquery-ui.theme.min.css'), true);
$kuler->addScript($kuler->getThemeResource('catalog/view/javascript/kuler/jquery-ui/jquery-ui.min.js'), true);
?>
<div class="kuler-filter">
    <div class="box kuler-module">
        <?php if ($show_title) { ?>
            <div class="box-heading"><span><?php echo $module_title ?></span></div>
        <?php } ?>
        <div class="box-content">
	        <form action="<?php echo $link; ?>" method="get" class="kuler-filter-form">
		        <!--<input type="hidden" name="route" value="module/kuler_filter_result" />-->
	            <?php if ($setting['category'] && !empty($categories)) { ?>
		            <div class="filter-group">
			            <p><?php echo $__['text_filter_by_category']; ?></p>
		                <div class="catfilter">
			                <!--<select name="category_id" class="dropdownbox">
				                <option value="0"><?php echo $__['text_all']; ?></option>-->
				                <?php foreach ($categories as $category) { ?>
				                <!--<option value="<?php echo $category['category_id']; ?>"<?php if (in_array($category['category_id'], $selected_category_ids)) echo ' selected="selected"'; ?>><?php echo $category['name']; ?></option>-->
            <label for="kuler-filter-option-<?php echo $category['category_id']; ?>">
			<input type="checkbox" id="kuler-filter-option-<?php echo $category['category_id']; ?>" value="<?php echo $category['category_id']; ?>" name="category_id"><span><?php echo $category['name']; ?></span></label><br/>
			                    <?php } ?>
			                <!--</select>-->
		                </div>
		            </div>
	            <?php } ?>

	            <?php if ($setting['manufacture'] && !empty($manufacturers)) { ?>
	                <div class="filter-group">
		                <div>
			                <p><?php echo $__['text_filter_by_manufacturer']; ?></p>
			                <select name="manufacturer_id">
				                <option value="0"><?php echo $__['text_all']; ?></option>
			                    <?php foreach ($manufacturers as $item) { ?>
				                <option value="<?php echo $item['manufacturer_id']; ?>"<?php if (in_array($item['manufacturer_id'], $selected_manufacturer_ids)) echo ' selected="selected"'; ?>><?php echo $item['name']; ?></option>
			                    <?php } ?>
			                </select>
		                </div>
	                </div>
	            <?php } ?>

	            <?php if ($setting['attribute'] && !empty($attributes)) { ?>
		            <div class="filter-group">
		                <p><?php echo $__['text_filter_by_attributes']; ?></p>

						<?php if (!empty($selected_attributes)) { ?>
			            <div>
				            <p><?php echo $__['text_current_attribute_filter']; ?></p>
				            <ul>
					            <?php foreach ($selected_attributes as $attribute_id => $attribute_values) { ?>
					                <?php foreach ($attribute_values as $attribute_value) { ?>
							            <li class="remove-selected-filter">
								            <input type="hidden" name="attribute_id" value="<?php echo $attribute_id; ?>!<?php echo $attribute_value['value']; ?>" />
								            <a href="<?php echo $attribute_value['link']; ?>">
									            <?php echo $__['text_clear']; ?> <strong><?php echo $attributes[$attribute_id]['name']; ?> > <?php echo $attribute_value['value']; ?></strong> <?php echo $__['text_filter']; ?>
								            </a>
							            </li>

							            <?php unset($attributes[$attribute_id]['values'][$attribute_value['value']]); ?>
							            <?php if (empty($attributes[$attribute_id]['values'][$attribute_value['value']])) unset($attributes[$attribute_id]); ?>
						            <?php } ?>
					            <?php } ?>
				            </ul>
			            </div>
			            <?php } ?>

			            <?php if (!empty($attributes)) { ?>
				            <div>
					            <?php if (!empty($selected_attributes)) { ?>
					            <p><?php echo $__['text_select_additional_attribute_filter']; ?></p>
					            <?php } ?>

					            <?php foreach ($attributes as $attribute) { ?>
						            <?php if (!empty($attribute['values'])) { ?>
							            <div>
								            <p><?php echo $attribute['name']; ?></p>

								            <?php $attribute_value_index = 0; ?>
								            <?php foreach ($attribute['values'] as $attribute_value) { ?>
									            <label for="kuler-filter-attribute-<?php echo $attribute['attribute_id']; ?>-<?php echo $attribute_value_index; ?>">
									                <input type="checkbox" name="attribute_id" value="<?php echo $attribute['attribute_id']; ?>!<?php echo $attribute_value['value']; ?>" id="kuler-filter-attribute-<?php echo $attribute['attribute_id']; ?>-<?php echo $attribute_value_index; ?>" /> <?php echo $attribute_value['value']; ?>
									            </label>
									            <br />
									            <?php $attribute_value_index++; ?>
								            <?php } ?>
							            </div>
							        <?php } ?>
					            <?php } ?>
					        </div>
			            <?php } ?>
			        </div>
		        <?php } ?>

		        <?php if ($setting['option'] && $options) { ?>
		            <div class="filter-group">
						<p><?php echo $__['text_filter_by_options']; ?></p>

			            <?php if (!empty($selected_options)) { ?>
				            <div>
					            <p><?php echo $__['text_current_option_filter']; ?></p>
					            <ul>
						            <?php foreach ($selected_options as $option_id => $option_values) { ?>
							            <?php foreach ($option_values as $option_value) { ?>
								            <li class="remove-selected-filter">
									            <input type="hidden" name="<?php echo $options[$option_id]['values'][$option_value['value']]['type'] == 'multiple' ? 'option_value_id' : 'option_value'; ?>" value="<?php echo $option_id; ?>!<?php echo $option_value['value']; ?>" />
									            <a href="<?php echo $option_value['link']; ?>">
										            <?php echo $__['text_clear']; ?> <strong><?php echo $options[$option_id]['name']; ?> > <?php echo $options[$option_id]['values'][$option_value['value']]['name']; ?></strong> <?php echo $__['text_filter']; ?>
									            </a>
								            </li>

								            <?php unset($options[$option_id]['values'][$option_value['value']]); ?>
								            <?php if (empty($options[$option_id]['values'])) unset($options[$option_id]); ?>
								        <?php } ?>
						            <?php } ?>
					            </ul>
					        </div>
			            <?php } ?>

			            <?php if (!empty($options)) { ?>
				            <div>
					            <?php if (!empty($selected_options)) { ?>
					                <p><?php echo $__['text_select_additional_option_filter']; ?></p>
					            <?php } ?>
					            <?php foreach ($options as $option) { ?>
						            <?php if (!empty($option['values'])) { ?>
							            <div>
								            <p><?php echo $option['name']; ?></p>

								            <?php $option_value_index = 0; ?>
								            <?php foreach ($option['values'] as $value) { ?>
									            <label for="kuler-filter-option-<?php echo $option['option_id']; ?>-<?php echo $option_value_index; ?>">
										            <input type="checkbox" name="<?php echo $value['type'] == 'multiple' ? 'option_value_id' : 'option_value'; ?>" value="<?php echo $option['option_id']; ?>!<?php echo $value['value']; ?>" id="kuler-filter-option-<?php echo $option['option_id']; ?>-<?php echo $option_value_index; ?>" /> <?php echo $value['name']; ?>
									            </label>
									            <br />
									        <?php $option_value_index++; ?>
								            <?php } ?>
							            </div>
							        <?php } ?>
					            <?php } ?>
					        </div>
			            <?php } ?>
		            </div>
		        <?php } ?>

	            <?php if (isset($setting['price_filter']) && $setting['price_filter']) { ?>
	                <div>
	                    <p><?php echo $__['text_price']; ?> (<?php echo $setting['currency']; ?>) <span
	                            class="price-range"><?php echo "$price_min - $price_max"; ?></span></p>

	                    <div class="price-slide"></div>
	                    <input type="hidden" class="price-min" name="price_min" value="<?php echo $price_min; ?>" />
	                    <input type="hidden" class="price-max" name="price_max" value="<?php echo $price_max; ?>" />
	                    <input type="hidden" class="currency-code" name="currency_code" value="<?php echo $setting['currency']; ?>" />
	                </div>
	            <?php } ?>

	            <div class="buttons">
	                <div class="right">
	                    <button type="submit" class="button"><?php echo $__['text_search']; ?></button>
	                </div>
	            </div>
	        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php if (isset($setting['price_filter']) && $setting['price_filter']) { ?>
    $(function () {
        var settings = <?php echo json_encode($setting); ?>,
            priceMin = <?php echo $price_min; ?>,
            priceMax = <?php echo $price_max; ?>,
            $priceSlide = $('.price-slide'),
            $range = $('.price-range'),
            $priceMin = $('.price-min'),
            $priceMax = $('.price-max');

        $priceSlide.slider({
            animate: false,
            min: settings['price_min'],
            max: settings['price_max'],
            values: [priceMin, priceMax],
            range: true,
            slide: function (event, ui) {
                var rangeHtml = '';

                rangeHtml = ui.values[0] + ' - ' + ui.values[1];

                $priceMin.val(ui.values[0]);
                $priceMax.val(ui.values[1]);

                $range.html(rangeHtml);
            },
            change: function () {
                $priceMin.trigger('change');
                $priceMax.trigger('change');
            }
        });
    });
    <?php } ?>

    $('.kuler-filter-form').on('submit', function (evt) {
	    evt.preventDefault();

	    var $this = $(this),
		    queries = {};

	    // Get checked input
	    $this.find('input[type="checkbox"]:checked, input[type="hidden"], select').each(function () {
		    if (!queries.hasOwnProperty(this.name)) {
			    queries[this.name] = [];
		    }

		    queries[this.name].push(encodeURIComponent(this.value));
	    });

	    // Build query url
	    var query = [];
	    for (var key in queries) {
		    query.push(key + '=' + queries[key].join('!!'));
	    }

	    // Query now
	    window.location = this.action + '&' + query.join('&');
    });
</script>
<style>
.catfilter span {
    margin-left: 5px;
}
</style>