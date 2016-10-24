INSERT INTO `oc_emailtemplate` (`emailtemplate_key`, `emailtemplate_label`, `emailtemplate_type`, `emailtemplate_template`, `emailtemplate_mail_to`, `emailtemplate_mail_cc`, `emailtemplate_mail_bcc`, `emailtemplate_mail_from`, `emailtemplate_mail_sender`, `emailtemplate_mail_replyto`, `emailtemplate_mail_replyto_name`, `emailtemplate_mail_attachment`, `emailtemplate_attach_invoice`, `emailtemplate_language_files`, `emailtemplate_wrapper_tpl`, `emailtemplate_tracking_campaign_source`, `emailtemplate_status`, `emailtemplate_default`, `emailtemplate_showcase`, `emailtemplate_mail_plain_text`, `emailtemplate_condition`, `emailtemplate_modified`, `emailtemplate_config_id`, `store_id`, `customer_group_id`, `order_status_id`) VALUES
('admin.customer_approve', 'Customer Approve', 'customer', '', '', '', '', '', '', '', '', '', 0, 'mail/customer', '', '', 1, 1, 1, 0, '', NOW(), 0, NULL, 0, 0);

INSERT INTO `oc_emailtemplate_description` (`emailtemplate_id`, `language_id`, `emailtemplate_description_subject`, `emailtemplate_description_preview`, `emailtemplate_description_content1`, `emailtemplate_description_content2`, `emailtemplate_description_content3`, `emailtemplate_description_comment`, `emailtemplate_description_unsubscribe_text`) VALUES
({_ID}, 0, 'Welcome {$customer_firstname} and thank you for registering at {$store_name}!', '', '&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot;&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td width=&quot;2&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n			&lt;td class=&quot;heading2&quot;&gt;&lt;strong&gt;{$store_name}&lt;/strong&gt;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td height=&quot;3&quot; style=&quot;font-size:1px; line-height:0;&quot; width=&quot;2&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n			&lt;td height=&quot;3&quot; style=&quot;font-size:1px; line-height:0;&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td bgcolor=&quot;#cccccc&quot; height=&quot;1&quot; style=&quot;font-size:1px; line-height:0;&quot; width=&quot;2&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n			&lt;td bgcolor=&quot;#cccccc&quot; height=&quot;1&quot; style=&quot;font-size:1px; line-height:0;&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td height=&quot;15&quot; style=&quot;font-size:1px; line-height:0;&quot; width=&quot;2&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n			&lt;td height=&quot;15&quot; style=&quot;font-size:1px; line-height:0;&quot;&gt;&amp;nbsp;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;div class=&quot;link&quot;&gt;&lt;b&gt;{$text_welcome}&lt;/b&gt;&lt;br /&gt;\r\n&lt;span class=&quot;icon&quot;&gt;»&lt;/span&gt;&amp;nbsp;&lt;a href=&quot;{$account_login_tracking}&quot; target=&quot;_blank&quot;&gt;&amp;nbsp;&lt;b&gt;{$account_login}&lt;/b&gt;&amp;nbsp;&lt;/a&gt;&lt;br /&gt;\r\n&amp;nbsp;&lt;/div&gt;\r\n&lt;strong&gt;{$text_name}&amp;nbsp;&lt;/strong&gt;&amp;nbsp;{$customer_firstname}&amp;nbsp;{$customer_lastname}&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;strong&gt;{$text_email}&amp;nbsp;&lt;/strong&gt;&amp;nbsp;{$customer_email}&lt;br /&gt;\r\n&lt;br /&gt;\r\n{$text_services}&lt;br /&gt;\r\n&amp;nbsp;\r\n&lt;div class=&quot;last&quot;&gt;{$text_thanks}&lt;br style=&quot;line-height:18px;&quot; /&gt;\r\n&lt;a href=&quot;{$store_url_tracking}&quot; target=&quot;_blank&quot;&gt;{$store_name}&lt;/a&gt;&lt;/div&gt;\r\n', '', '', '', '');