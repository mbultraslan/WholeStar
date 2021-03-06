<?php
$_['heading_module_title']      = 'Kuler BLog Manager - Latest Comments';
if (!empty($_GET['route']) && strpos($_GET['route'], 'design/layout') !== false) {
	$_['heading_title']             = $_['heading_module_title'];
} else {
	$_['heading_title']             = '<span style="color: #E1704B; font-weight: bold;">'. $_['heading_module_title'] .'</span>';
}

// Text
$_['text_module']               = 'Modules';
$_['text_module_title']         = 'Module';
$_['text_success']              = 'Success: You have modified module ' . $_['heading_module_title'] . '!';

$_['text_content_top']          = 'Content Top';
$_['text_content_bottom']       = 'Content Bottom';
$_['text_column_left']          = 'Column Left';
$_['text_column_right']         = 'Column Right';

$_['text_display_setting']      = 'Display Setting';

// Entry
$_['entry_module_name']         = 'Module Name:';
$_['entry_store']               = 'Store:';
$_['entry_status']              = 'Status:';
$_['entry_show_title']          = 'Show Title:';
$_['entry_title']               = 'Title:';
$_['entry_layout']              = 'Layout:';
$_['entry_position']            = 'Position:';
$_['entry_sort_order']          = 'Sort Order:';

$_['entry_exclude_category']    = 'Exclude Category:';
$_['entry_limit']               = 'Limit:';
$_['entry_avatar_image_size']   = 'Avatar Image Size:';

// Error
$_['error_warning']             = 'Warning: You can not modify the module! Check error below.';
$_['error_permission']          = 'Warning: You do not have permission to modify module '. $_['heading_module_title'] .'!';
$_['error_avatar_size']         = 'Avatar size required!';