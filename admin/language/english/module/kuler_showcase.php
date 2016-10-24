<?php
$_['heading_kuler_module']      = 'Showcase';
$_['heading_module']            = 'Kuler Showcase';

if (!empty($_GET['route']) && strpos($_GET['route'], 'design/layout') !== false) {
	$_['heading_title']             = $_['heading_module'];
} else {
	$_['heading_title']             = '<span style="color: #E1704B; font-weight: bold;">'. $_['heading_module'] .'</span>';
}

$_['text_modules']              = 'Modules';
$_['text_module']               = 'Module';

$_['text_success']              = 'Success: You have modified ' . $_['heading_kuler_module'] . '!';
$_['error_permission']          = 'Warning: You do not have permission to modify module '. $_['heading_kuler_module'] .'!';