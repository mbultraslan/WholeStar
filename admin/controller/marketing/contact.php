<?php
class ControllerMarketingContact extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_default'] = $this->language->get('text_default');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_customer_all'] = $this->language->get('text_customer_all');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_message'] = $this->language->get('entry_message');

		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_preheader'] = $this->language->get('entry_preheader');
		$data['entry_campaign_name'] = $this->language->get('entry_campaign_name');
		$data['warning_template_content'] = $this->language->get('warning_template_content');
		$data['text_select'] = $this->language->get('text_select');

		$this->load->model('localisation/language');
		$this->load->model('module/emailtemplate');

        $templates = $this->model_module_emailtemplate->getTemplates(array(
			'emailtemplate_key' => 'admin.newsletter'
		));

		$data['email_templates'] = array();

		foreach($templates as $row) {
			$label = $row['emailtemplate_label'];

			if ($row['emailtemplate_default']) {
				$label = $this->language->get('text_default') . ' - ' . $label;
			}

			$data['email_templates'][] = array(
				'value' => $row['emailtemplate_id'],
				'label' => $label
			);
		}

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data['products'] = array();
		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
					$special = $product_special['price'];

					break;
				}
			}





			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'special'    => $special,
				'quantity'   => $result['quantity'],
// BOF - Zappo - Quick Edit - TWO LINES - Added Admin Category Filter and Quick Edit
				//'category'   => $this->model_catalog_product->getProductCategories($result['product_id']),
				//'quick'      => $this->url->link('catalog/product/quickedit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL').'" onclick="$(\'#quick-edit\').load(this.href).modal({backdrop:false}, \'show\');return false;',
			//	'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				//'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
		}








		$data['languages'] = $this->model_localisation_language->getLanguages();

		$config = $this->model_module_emailtemplate->getConfig(1, true, true);

        $data['campaign_name'] = $config['tracking_campaign_name'];

        $data['templates_action'] = $this->url->link('module/emailtemplate/get_template', 'token='.$this->session->data['token'], 'SSL');

		$data['help_customer'] = $this->language->get('help_customer');
		$data['help_affiliate'] = $this->language->get('help_affiliate');
		$data['help_product'] = $this->language->get('help_product');

		$data['button_send'] = $this->language->get('button_send');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['cancel'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/contact_lang.tpl', $data));
	}

	public function send() {


		$this->load->language('marketing/contact');
$this->load->model('tool/image');
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'marketing/contact')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if (!$this->request->post['subject']) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}

				$this->load->model('sale/customer');

				$this->load->model('sale/customer_group');

				$this->load->model('marketing/affiliate');

				$this->load->model('sale/order');



				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}

				$email_total = 0;

				$emails = array();

				switch ($this->request->post['to']) {
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 1000000
						);

						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[$result['customer_id']] = array(
								'email' => $result['email'],
								'customer_id' => $result['customer_id'],
								'store_id' => $result['store_id'],
								'language_id' => $result['language_id']
							);
						}
						break;



						case 'subs':


								$customer_info = $this->model_sale_customer->getsubscribedcustomers();



								if ($customer_info) {

								foreach ($customer_info as $result) {
							$emails[] = array(
								//'customer_id' =>0,
										'email' => $result['news_email'],
							//		'affiliate_id' =>0,
									);
								}
								}

						break;
						//print_r($emails);
						//		exit();

					case 'customer_all':
						$customer_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 1000000
						);

						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[$result['customer_id']] = array(
								'email' => $result['email'],
								'customer_id' => $result['customer_id'],
								'store_id' => $result['store_id'],
								'language_id' => $result['language_id']
							);
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 1000000
						);

						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[$result['customer_id']] = array(
								'email' => $result['email'],
								'customer_id' => $result['customer_id'],
								'store_id' => $result['store_id'],
								'language_id' => $result['language_id']
							);
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_sale_customer->getCustomer($customer_id);

								if ($customer_info) {
									$email_total = 1;

									$emails[] = array(
										'customer' => $customer_info,
										'email' => $customer_info['email'],
										'customer_id' => $customer_info['customer_id'],
										'store_id' => $customer_info['store_id'],
										'language_id' => $customer_info['language_id']
									);
								}
							}
						}
						break;
					case 'affiliate_all':
						$affiliate_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 1000000
						);

						$email_total = $this->model_marketing_affiliate->getTotalAffiliates($affiliate_data);

						$results = $this->model_marketing_affiliate->getAffiliates($affiliate_data);

						foreach ($results as $result) {
							$emails[$result['affiliate_id']] = array(
								'email' => $result['email'],
								'affiliate_id' => $result['affiliate_id']
							);
						}
						break;
					case 'affiliate':
						if (!empty($this->request->post['affiliate'])) {
							foreach ($this->request->post['affiliate'] as $affiliate_id) {
								$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

								if ($affiliate_info) {
									$email_total = 1;

									$emails[$affiliate_info['affiliate_id']] = array(
										'affiliate' => $affiliate_info,
										'email' => $affiliate_info['email'],
										'affiliate_id' => $affiliate_info['affiliate_id']
									);
								}
							}
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

							foreach ($results as $result) {
								$emails[$result['customer_id']] = array(
								'email' => $result['email'],
								'customer_id' => $result['customer_id'],
								'store_id' => $result['store_id'],
								'language_id' => $result['language_id']
							);
							}
						}
						break;
				}

			//	print_r($emails);
			//	exit();

				if (!$emails) {
					$json['error']['warning'] = $this->language->get('warning_mail_recipients');
				} else {
					$start = ($page - 1) * 10;
					$end = $start + 10;

					if ($end < $email_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);
					} else {
						$json['success'] = sprintf($this->language->get('text_success_sent'), $email_total);
					}

					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('marketing/contact/send', 'token=' . $this->session->data['token'] . '&page=' . ($page + 1), 'SSL'));
					} else {
						$json['next'] = '';
					}



						// add selected products to template

						$this->load->model('catalog/product');


						$producttosend=null;

						if (isset($_POST['selected_product'])) {



							foreach ($this->request->post['selected_product'] as $product_id) {
							$productdata="";
							$product_info = $this->model_catalog_product->getProductformail($product_id);

							if($product_info){
								foreach ($product_info as $d){
									$name = $d['name'];
									$model = $d['model'];
									$price = $d['price'];
									$image= $d['image'];

								}

								if (is_file(DIR_IMAGE . $image)) {
									$imagepath = $this->model_tool_image->resize($image, 500, 500);
								} else {
									$imagepath = $this->model_tool_image->resize('no_image.png', 500, 500);
								}

								$special = false;

								$product_specials = $this->model_catalog_product->getProductSpecialsformail($product_id);

								foreach ($product_specials  as $product_special) {
									if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
										$special = $product_special['price'];
										$specialeachprice = $product_special['specialeachprice'];

									}
								}

								if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {

									$server_name= "https://".$_SERVER['SERVER_NAME'];
								}
								else {
									$server_name= "http://".$_SERVER['SERVER_NAME'];
								}

								if (isset($this->request->post['link'])) {
									$href= $server_name.'/index.php?route=product/product&product_id='.$product_id;
								}
								else{
									$href="#";
								}


								$productdata.='<div class="ecxemailShowcaseItem ecxemailtemplateSpacing">';
								$productdata.='<table width="200" height="400" align="left" style="border:none;border-collapse:collapse;table-layout:fixed;width:200px;" border="0" cellspacing="0" cellpadding="0">';
								$productdata.='<tbody>';
								$productdata.='<tr>';
								$productdata.='<td align="center" class="img" valign="top" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;padding:0;text-align:center;vertical-align:top;height:120px;">';
								$productdata.='<a title="High Neck Lace Top Midi Dress - High Neck Lace Top Midi Dress,Short sleeve, key hole front, Hidden back zip closure." class="ecxemailtemplateNoDisplay" style="color:#333333;text-decoration:none;" href="'.$href.'" target="_blank">';
								$productdata.='<img  height="220" style="-ms-interpolation-mode:bicubic;border:none;line-height:100%;max-width:100% !important;text-decoration:none;" alt="" src="'.$imagepath.'"></a>';
								$productdata.='</td>';
								$productdata.='</tr>';
								$productdata.='<tr>';
								$productdata.='<td class="ecxprice" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:11px;line-height:18px;padding:3px 0 0 0;text-align:center;vertical-align:top;height:10px;">';
								$productdata.='<div class="ecxprice-inner" style="border-top:1px dotted #ccc;display:inline-block;padding:0 2px 0 2px;zoom:1;">';
								$productdata.='</div>';
								$productdata.='</td>';
								$productdata.='</tr>';
								$productdata.='<tr>';
								if (isset($this->request->post['name'])) {
									$productdata.='<td align="center" class="ecxproductTitle" valign="bottom" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:14px;padding:0;text-align:center;vertical-align:bottom;font-weight:bold;">';
									$productdata.='<a title="'.$name.'" style="color:#333333;text-decoration:none;" href="'.$href.'" target="_blank">'.$name.'</a>';
									$productdata.='</td>';
									$productdata.='</tr>';
								}
								if (isset($this->request->post['model'])) {
									$productdata.='<tr>';
									$productdata.='<td align="left" class="ecxproductTitle" valign="bottom" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:14px;padding:0;text-align:center;vertical-align:bottom;">';
									$productdata.='Model : '.$model;
									$productdata.='</td>';
									$productdata.='</tr>';
								}

								if (isset($this->request->post['special']) && $special) {

									if (isset($this->request->post['pack'])) {
										$productdata.='<tr>';
										$productdata.='<td align="left" class="ecxproductTitle" valign="bottom" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:14px;padding:0;text-align:center;vertical-align:bottom; font-weight:bold;">';
										$productdata.='Pack Price : <span style="font-size:10px; color:red; text-decoration: line-through" >£'.$price.'</span>  £'.$special;
										$productdata.='</td>';
										$productdata.='</tr>';
									}
								}
								else{

									if (isset($this->request->post['pack'])) {
										$productdata.='<tr>';
										$productdata.='<td align="left" class="ecxproductTitle" valign="bottom" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:14px;padding:0;text-align:center;vertical-align:bottom; font-weight:bold;">';
										$productdata.='Pack Price : £'.$price;
										$productdata.='</td>';
										$productdata.='</tr>';
									}
								}
								$productdata.='<tr>';
								$productdata.='<td class="ecxprice" style="color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:11px;line-height:18px;padding:3px 0 0 0;text-align:center;vertical-align:top;height:20px;">';
								$productdata.='<div class="ecxprice-inner" style="border-top:1px dotted #ccc;display:inline-block;padding:0 2px 0 2px;zoom:1;">';
								$productdata.='</div>';
								$productdata.='</td>';
								$productdata.='</tr>';
								$productdata.='</tbody>';
								$productdata.='</table>';
								$productdata.='</div>';

								$producttosend.=$productdata;
							}
							}
						}

					foreach ($emails as $email_info) {
						//if (empty($email_info['customer_id']) && empty($email_info['affiliate_id'])) continue;


						$email = $email_info['email'];

						$file = DIR_SYSTEM . 'library/emailtemplate/email_template.php';

						if (file_exists($file)) {
							include_once($file);
						} else {
							trigger_error('Error: Could not load library ' . $file . '!');
							exit();
						}

						$template = new EmailTemplate($this->request, $this->registry);


						if (isset($email_info['customer'])) {
							$template->addData($email_info['customer']);
							unset($email_info['customer']);
						} elseif (isset($email_info['customer_id'])) {
							$customer_info = $this->model_sale_customer->getCustomer($email_info['customer_id']);
							$template->addData($customer_info);
						}


						if (isset($email_info['affiliate'])) {
							$template->addData($email_info['affiliate']);
							unset($email_info['affiliate']);
						} elseif (isset($email_info['affiliate_id'])) {
							$affiliate_info = $this->model_sale_affiliate->getAffiliate($email_info['affiliate_id']);
							$template->addData($affiliate_info);
						}

						if (isset($email_info['language_id']) && $email_info['language_id']) {
							$language_id = $email_info['language_id'];
						} else {
							$language_id = $this->config->get('config_language_id');
						}

	  					// Default store auto select from db
						if ($this->request->post['store_id'] == 0 && isset($email_info['store_id'])) {
 							$store_id = $email_info['store_id'];
						} else {
							$store_id = $this->request->post['store_id'];
						}

						$template_data = array(
							'key' => 'admin.newsletter',
							'store_id' => $store_id
						);

						$template->load($template_data);

						if (!empty($template->data['emailtemplate']['unsubscribe_text']) && in_array($this->request->post['to'], array('newsletter', 'customer_all', 'customer_group', 'customer'))) {
							$url = (isset($store_info['url']) ? $store_info['url'] : HTTP_CATALOG) . 'index.php?route=account/newsletter/unsubscribe&code='.md5($email);
							$template->data['unsubscribe'] = sprintf(html_entity_decode($template->data['emailtemplate']['unsubscribe_text'], ENT_QUOTES, 'UTF-8'), $url);
					    }

						if (is_array($this->request->post['subject']) && !empty($this->request->post['subject'][$language_id])) {
							$template->data['subject'] = $this->request->post['subject'][$language_id];
						}

						if (is_array($this->request->post['preview']) && !empty($this->request->post['preview'][$language_id])) {
							$template->data['preheader_preview'] = $this->request->post['preview'][$language_id];
						}

						if (is_array($this->request->post['message']) && !empty($this->request->post['message'][$language_id])) {
							$body = $this->request->post['message'][$language_id];



						} else {
							$body = $store_name;
						}

						$body.=$producttosend;



						$template->addData($email_info);

						$template->data['config']['tracking_campaign_name'] = $this->request->post['campaign_name'];

					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title></title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($message, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";



						if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
							$mail = new Mail($this->config->get('config_mail'));
							$mail->setTo($email);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender($store_name);
							$mail->setSubject($this->request->post['subject']);
							$template->build();

							$template->fetch(null, $body);

							$mail = $template->hook($mail);
							$mail->send();
							$template->sent();
						}
					}
				}
			}
		}

			echo "<br/>";
		echo $producttosend;
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode($json));






	}
}
